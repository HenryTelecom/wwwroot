<?php

namespace Shop\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ShopController extends Controller
{
    /**
     * @Route("/", name="shop_index")
     * @Method({"GET"})
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}
