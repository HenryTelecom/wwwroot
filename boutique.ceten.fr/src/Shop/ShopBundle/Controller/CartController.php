<?php

namespace Shop\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use JMS\Serializer\SerializationContext;

use Ceten\CetenBundle\Entity\User;
use Ceten\CetenBundle\Entity\Product;
use Ceten\CetenBundle\Entity\ProductOrder;
use Ceten\CetenBundle\Entity\ShopOrder;

use Datetime;

/**
 * @Route("/panier")
 */
class CartController extends Controller
{

    /**
     * Create an order using Cart cookie
     * @param  boolean $changed
     */
    private function getOrder(&$changed)
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $em = $this->container->get('doctrine.orm.entity_manager');
        
        $cookieName = $this->container->getParameter('shop.shop_cart');
        $cart = array();

        if ($request->cookies->has($cookieName)) {
            $cart = json_decode(base64_decode($request->cookies->get($cookieName)), true);
        }

        $ids = array_keys($cart);

        $products = array();

        if (!empty($ids)) {
            $qb = $em
                    ->getRepository('CetenCetenBundle:Product')
                    ->createQueryBuilder('p');

            $products = $qb->where($qb->expr()->in('p.id', $ids))->getQuery()->getResult();
        }

        $shopOrder = new ShopOrder();
        foreach ($products as $product) {
            $order = new ProductOrder();

            $count = $cart[$product->getId()];

            if ($count > $product->getStock()) {
                $count = $product->getStock();
                $changed = true;
            }

            if ($count > $this->container->getParameter('shop.max_order')) {
                $count = $this->container->getParameter('shop.max_order');
                $changed = true;
            }

            $order->setCount($count);
            $order->setProduct($product);

            if (!$count) {
                continue;
            }

            $shopOrder->addOrder($order);
        }

        return $shopOrder;
    }

    /**
     * Create the cookie for an order
     * @param  ShopOrder $order
     */
    private function createCookie(ShopOrder $order)
    {
        $cookieName = $this->container->getParameter('shop.shop_cart');
        $cookie = array();
        foreach ($order->getOrders() as $o) {
            $cookie[$o->getProduct()->getId()] = $o->getCount();
        }

        $value = base64_encode(json_encode($cookie));
        return new Cookie($cookieName, $value, 0, '/', null, false, false);
    }

    /**
     * @Route(".{_format}", name="shop_cart_list", defaults={"_format": "html"})
     * @Method({"GET"})
     * @Template("::layout.html.twig")
     */
    public function indexAction($_format)
    {
        $order = $this->getOrder($changed);
        $cookie = $this->createCookie($order);

        if ($_format === 'json') {
            $json = $this
                        ->container
                        ->get('jms_serializer')
                        ->serialize($order, 'json', SerializationContext::create()->setGroups(array('order_detail')));
            $response = new Response($json, 200, array(
                'Context-Type' => 'application/json; charset=utf-8'
            ));

            $response->headers->setCookie($cookie);

            return $response;
        }
        return array();
    }

    /**
     * @Route("/commander", name="shop_cart_order")
     * @Method({"POST"})
     */
    public function orderAction(Request $request)
    {
        $order = $this->getOrder($changed);
        $req = json_decode($request->getContent(), true);

        if (null !== $req) {
            $request->request->replace($req);
        }
        $req = $request->request;

        if ($changed) {
            $response = array('ok' => false, 'code' => 1);
        } else {
            if (null !== ($user = $this->getUser()) && $user instanceof User) {
                if (!$order->getOrders()->isEmpty()) {
                
                    // Persist order
                    $em = $this->container->get('doctrine.orm.entity_manager');
                    $order->setUser($user);

                    

                    if($req->has('type') && 1 == $req->get('type')) {
                        $order->setPayment(4);
                    }

                    $order->setCreated(new Datetime());
                    $order->calculateTotal();

                    $em->getRepository('CetenCetenBundle:Product')->updateStock($order);

                    $em->persist($order);
                    $em->flush();

                    // Clear cart
                    $cookie = new Cookie($this->container->getParameter('shop.shop_cart'), base64_encode('{}'), 0, '/', null, false, false);
                    
                    $response = array('ok' => true, 'id' => $order->getId());
                } else {
                    $response = array('ok' => false, 'code' => 3);
                }

            } else {
                $response = array('ok' => false, 'code' => 2);
            }
        }
        
        if (!isset($cookie)) {
            $cookie = $this->createCookie($order);
        }

        $response = new Response(json_encode($response), 200, array(
                'Context-Type' => 'application/json; charset=utf-8'
            ));

        $response->headers->setCookie($cookie);
        return $response;
    }
}
