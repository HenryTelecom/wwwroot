<?php

namespace Ceten\CetenBundle\Ceten;

use Symfony\Component\DependencyInjection\ContainerAware;

class Ceten extends ContainerAware 
{
    /**
     * 
     * @return [type] [description]
     */
    public function getServices() 
    {
        return array(
            array(
                'label'     => 'CETEN',
                'url'       => $this->container->getParameter('ceten.websites.www'),
                'role'      => ''
            ),
            array(
                'label'     => 'Boutique',
                'url'       => $this->container->getParameter('ceten.websites.shop'),
                'role'      => ''
            ),
            array(
                'label'     => 'Dashboard',
                'url'       => $this->container->getParameter('ceten.websites.dashboard'),
                'role'      => 'ROLE_ADMIN'
            )
        );
    }

    /**
     * 
     * @param  [type] $service [description]
     * @return [type]          [description]
     */
    public function getServiceUrl($service)
    {
        try {
            $url = $this->container->getParameter('ceten.websites.' . $service);
            return $this->container->get('ceten.twig.ceten_extension')->external($url);
        } catch (InvalidArgumentException $e) {
            return null;
        }
    }
}