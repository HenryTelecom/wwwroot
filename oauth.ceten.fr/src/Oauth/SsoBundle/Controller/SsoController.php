<?php

namespace Oauth\SsoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class SsoController extends Controller
{
    const SERVICE = 'service';

    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {
        $service = null;
        if ($request->getSession()->has(self::SERVICE)) {
            $service = $request->getSession()->get(self::SERVICE);
            $request->getSession()->remove(self::SERVICE);
        } else if ($request->query->has(self::SERVICE)) {
            $service = $request->query->get(self::SERVICE);
        }

        if (!empty($service) && null !== ($url = $this->container->get('ceten')->getServiceUrl($service))) {
            return $this->redirect($url);
        }

        if (!$this->container->get('security.context')->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('sso_signin'));
        }

        return $this->redirect($this->generateUrl('sso_profile'));
    }

    /**
     * @Route("/signin", name="sso_signin")
     * @Method({"GET"})
     * @Template()
     */
    public function signinAction(Request $request)
    {
        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedHttpException('You are already logged in');
        }

        if ($request->query->has('service')) {
            $request->getSession()->set('service', $request->query->get('service'));
        }
        return array();
    }

    /**
     * @Route("/signin", name="sso_signin_check")
     * @Method({"POST"})
     * @Template("OauthSsoBundle:Sso:signin.html.twig")
     */
    public function signinCheckAction()
    {
        return array();
    }

    /**
     * @Route("/signup", name="sso_signup")
     * @Method({"GET"})
     * @Template()
     */
    public function signupAction()
    {
        return array();
    }

    /**
     * @Route("/signup", name="sso_signup_check")
     * @Method({"POST"})
     * @Template("OauthSsoBundle:Sso:signup.html.twig")
     */
    public function signupCheckAction()
    {
        return array();
    }
}
