<?php

namespace Shop\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * @Route("/categories")
 */
class TagController extends Controller
{
    /**
     * @Route("/{slug}", name="shop_tag_slug")
     * @Method({"GET"})
     * @Template()
     */
    public function slugAction($slug)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $tag = $em->getRepository('CetenCetenBundle:Tag')->findOneBy(array('slug' => $slug));

        if (!$tag) {
            throw new NotFoundHttpException(sprintf('Tag "%s" not found', $slug));
        }

        return array(
            'tag' =>  $tag
        );
    }
}
