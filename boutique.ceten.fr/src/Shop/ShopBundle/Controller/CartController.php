<?php

namespace Shop\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use JMS\Serializer\SerializationContext;

use Ceten\CetenBundle\Entity\Product;
use Ceten\CetenBundle\Entity\ProductOrder;

/**
 * @Route("/panier")
 */
class CartController extends Controller
{
    /**
     * @Route(".{_format}", name="shop_cart_list", defaults={"_format": "html"})
     * @Method({"GET"})
     * @Template("::layout.html.twig")
     */
    public function indexAction(Request $request, $_format)
    {
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

        $orders = array();
        foreach ($products as $product) {
            $order = new ProductOrder();

            $count = $cart[$product->getId()];

            if ($count > $product->getStock()) {
                $count = $product->getStock();
            }

            if ($count > $this->container->getParameter('shop.max_order')) {
                $count = $this->container->getParameter('shop.max_order');
            }

            $order->setCount($count);
            $order->setProduct($product);

            $orders[] = $order;
        }

        $cookie = array();
        foreach ($orders as $order) {
            $cookie[$order->getProduct()->getId()] = $order->getCount();
        }

        $value = base64_encode(json_encode($cookie));
        $cookie = new Cookie($cookieName, $value, 0, '/', null, false, false);

        if ($_format === 'json') {
            $json = $this
                        ->container
                        ->get('jms_serializer')
                        ->serialize($orders, 'json', SerializationContext::create()->setGroups(array('order_detail')));

            $response = new Response($json, 200, array(
                'Context-Type' => 'application/json; charset=utf-8'
            ));

            $response->headers->setCookie($cookie);

            return $response;
        }
        return array();
    }
}
