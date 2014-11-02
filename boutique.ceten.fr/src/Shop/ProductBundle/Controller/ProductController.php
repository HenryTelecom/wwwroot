<?php

namespace Shop\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use JMS\Serializer\SerializationContext;

/**
 * @Route("/produits")
 */
class ProductController extends Controller
{
    /**
     * @Route(".{_format}", name="shop_product_list", defaults={"_format": "html"})
     * @Method({"GET"})
     * @Template("::layout.html.twig")
     */
    public function indexAction($_format)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $products = $em->getRepository('CetenCetenBundle:Product')->findBy(array('homepage' => true));

        if ($_format === 'json') {
            $json = $this
                        ->container
                        ->get('jms_serializer')
                        ->serialize($products, 'json', SerializationContext::create()->setGroups(array('product_list')));

            return new Response($json, 200, array(
                'Context-Type' => 'application/json; charset=utf-8'
            ));
        }
        return array();
    }


    /**
     * @Route("/{slug}.{_format}", name="shop_product_slug", defaults={"_format": "html"})
     * @Method({"GET"})
     * @Template("::layout.html.twig")
     */
    public function slugAction($slug, $_format)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $product = $em->getRepository('CetenCetenBundle:Product')->findOneBy(array('slug' => $slug));

        if (!$product) {
            throw new NotFoundHttpException(sprintf('Product "%s" not found', $slug));
        }

        if ($_format === 'json') {
            $json = $this
                        ->container
                        ->get('jms_serializer')
                        ->serialize($product, 'json', SerializationContext::create()->setGroups(array('product_detail')));

            return new Response($json, 200, array(
                'Context-Type' => 'application/json; charset=utf-8'
            ));
        }

        return array(
            'product' =>  $product
        );
    }
}
