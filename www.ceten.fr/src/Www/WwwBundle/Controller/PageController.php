<?php

namespace Www\WwwBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PageController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template("::layout.html.twig")
     */
    public function indexAction()
    {
        return array();
    }
}
