<?php

namespace Oauth\SsoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class ProfileController extends Controller
{
    /**
     * @Route("/profile", name="sso_profile")
     * @Method({"GET"})
     * @Template()
     */
    public function indexAction()
    {
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('sso_signin'));
        }
        
        return array();
    }
}