<?php

namespace Shop\ShopBundle\Listener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpKernel\HttpKernelInterface;


class RequestListener extends ContainerAware
{
    public function onKernelResponse(FilterResponseEvent $event)
    {

        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $cookieName = $this->container->getParameter('shop.shop_cart');

        $request = $this->container->get('request_stack')->getCurrentRequest();
        if (!$request->cookies->has($cookieName)) {
            $cookie = new Cookie($cookieName, base64_encode('{}'), 0, '/', null, false, false);
            $event->getResponse()->headers->setCookie($cookie);
        }
    }
}