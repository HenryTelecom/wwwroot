<?php

namespace Ceten\SsoBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RequestListener extends ContainerAware
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernel::MASTER_REQUEST != $event->getRequestType()) {
            return;
        }
        $ssoSession = $this->container->getParameter('sso_session');
        if (!$event->getRequest()->cookies->has($ssoSession)) {

            $extension = $this->container->get('ceten.twig.ceten_extension');
            $url = $extension->external($this->container->getParameter('ceten.websites.oauth'));
            $url .= '?service=' . $this->container->getParameter('sso.service');
            $event->setResponse(new RedirectResponse($url));
        }
    }
}