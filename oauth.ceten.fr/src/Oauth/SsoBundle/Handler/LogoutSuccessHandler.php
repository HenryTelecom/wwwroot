<?php

namespace Oauth\SsoBundle\Handler;

use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerAware;

class LogoutSuccessHandler extends ContainerAware implements LogoutSuccessHandlerInterface
{

    /**
     * {@inheritDoc}
     */
    public function onLogoutSuccess(Request $request)
    {
        $service = $request->query->get('service');

        if (!empty($service) && null !== ($url = $this->container->get('ceten')->getServiceUrl($service))) {
            return new RedirectResponse($url);
        }

        return new RedirectResponse($this->container->get('router')->generate('sso_signin'));
    }
}