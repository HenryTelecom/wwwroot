<?php

namespace Shop\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use JMS\Serializer\SerializationContext;

class ShopController extends Controller
{
    /**
     * @Route("/", name="shop_index")
     * @Method({"GET"})
     * @Template("::layout.html.twig")
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/mes-commandes.{_format}", name="shop_order_list", defaults={"_format": "html"})
     * @Method({"GET"})
     * @Template("::layout.html.twig")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function orderListAction($_format)
    {
        $user = $this->getUser();

        if ($_format === 'json') {
            $em = $this->container->get('doctrine.orm.entity_manager');
            $orders = $em->getRepository('CetenCetenBundle:ShopOrder')->findBy(array('user' => $user), array('created' => 'DESC'));

            $json = $this
                        ->container
                        ->get('jms_serializer')
                        ->serialize($orders, 'json', SerializationContext::create()->setGroups(array('order_list')));
            return new Response($json, 200, array(
                'Context-Type' => 'application/json; charset=utf-8'
            ));
        }
        return array();
    }
}
