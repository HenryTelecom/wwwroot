<?php

namespace Www\WwwBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use JMS\Serializer\SerializationContext;

class PageController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method({"GET"})
     * @Template("::layout.html.twig")
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/news.{_format}", name="news_list", defaults={"_format": "html"})
     * @Method({"GET"})
     * @Template("::layout.html.twig")
     */
    public function newsAction($_format)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $news = $em->getRepository('CetenCetenBundle:News')->findBy(array(), array('created' => 'DESC'));
        if ($_format === "json") {
            $json = $this
                        ->container
                        ->get('jms_serializer')
                        ->serialize($news, 'json', SerializationContext::create()->setGroups(array('news_list')));

            return new Response($json, 200, array(
                'Context-Type' => 'application/json; charset=utf-8'
            ));   
        }
        return array();
    }

    /**
     * @Route("/partenaires.{_format}", name="partner_list", defaults={"_format": "html"})
     * @Method({"GET"})
     * @Template("::layout.html.twig")
     */
    public function partnerAction($_format)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $partners = $em->getRepository('CetenCetenBundle:Partner')->findBy(array(), array('name' => 'ASC'));
        if ($_format === "json") {
            $json = $this
                        ->container
                        ->get('jms_serializer')
                        ->serialize($partners, 'json', SerializationContext::create()->setGroups(array('partner_list')));

            return new Response($json, 200, array(
                'Context-Type' => 'application/json; charset=utf-8'
            ));   
        }
        return array();
    }
}
