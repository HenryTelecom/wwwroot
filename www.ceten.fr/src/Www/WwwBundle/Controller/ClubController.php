<?php

namespace Www\WwwBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use JMS\Serializer\SerializationContext;
/**
 * @Route("/clubs")
 */
class ClubController extends Controller
{
    /**
     * @Route(".{_format}", name="club_list", defaults={"_format": "html"})
     * @Method({"GET"})
     * @Template("::layout.html.twig")
     */
    public function clubAction($_format)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $clubs = $em->getRepository('CetenCetenBundle:Club')->findBy(array(), array('name' => 'ASC'));
        if ($_format === "json") {
            $json = $this
                        ->container
                        ->get('jms_serializer')
                        ->serialize($clubs, 'json', SerializationContext::create()->setGroups(array('club_list')));

            return new Response($json, 200, array(
                'Context-Type' => 'application/json; charset=utf-8'
            ));   
        }
        return array();
    }
}
