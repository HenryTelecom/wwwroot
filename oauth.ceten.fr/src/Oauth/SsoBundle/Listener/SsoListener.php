<?php

namespace Oauth\SsoBundle\Listener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use Ceten\CetenBundle\Entity\User;
use Ceten\CetenBundle\Entity\Session;


class SsoListener extends ContainerAware
{
    public function onKernelRequest()
    {
        $context = $this->container->get('security.context');
        if (null !== $token = $this->container->get('security.context')->getToken()) {
            
            // Change ceten state for authenticated user
            if (null !== ($user = $token->getUser())) {
                if (!$user instanceof User) {
                    return;
                }

                $em = $this->container->get('doctrine.orm.entity_manager');
                $member = $em->getRepository('CetenCetenBundle:Member')->findOneBy(array('email' => $user->getUsername()));

                if ($member) {
                    $user->setCeten(true);
                } else {
                    $user->setCeten(false);
                }
                $em->flush();
            }
        }
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {

        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $ssoSession = $this->container->getParameter('sso_session');

        $request = $this->container->get('request_stack')->getCurrentRequest();

        if (!$request->cookies->has($ssoSession)) {
            $id = sha1(uniqid('', true));
            $cookie = new Cookie($ssoSession, $id, 0, '/', '.ceten.fr');
            $event->getResponse()->headers->setCookie($cookie);
        } else {
            $id = $request->cookies->get($ssoSession);
        }

        $user = null;
        if (null !== $token = $this->container->get('security.context')->getToken()) {
            $user = $token->getUser();

            if (!$user instanceof User) {
                $user = null;
            }
        }

        $em = $this->container->get('doctrine.orm.entity_manager');
        $session = $em->getRepository('CetenCetenBundle:Session')->findOneBy(array('name' => $id));
        
        if (!$session) {
            $session = new Session();
            $session->setName($id);
        }

        $session->setUser($user);

        if (!$session->getId()) {
            $em->persist($session);
        }

        $em->flush();
    }
}
