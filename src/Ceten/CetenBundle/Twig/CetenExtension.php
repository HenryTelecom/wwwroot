<?php

namespace Ceten\CetenBundle\Twig;

use Twig_Extension;
use Twig_SimpleFunction;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CetenExtension extends Twig_Extension
{
    /**
     * Service container
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('cdn', array($this, 'cdn')),
            new Twig_SimpleFunction('oauth', array($this, 'oauth')),
            new Twig_SimpleFunction('external', array($this, 'external')),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getGlobals()
    {
        return array(
            'ceten' => $this->container->get('ceten'),
            'service_name' => $this->container->hasParameter('sso.service') ? '?service=' . $this->container->getParameter('sso.service') : ''
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ceten_extension';
    }

    /**
     * Whether debug is enabled
     * @return boolean [description]
     */
    public function isDebug() {
        return $this->container->get('kernel')->isDebug();
    }
    
    /**
     * cdn Twig function
     * @param string $url CDN file
     *
     * $url must start with a slash
     */
    public function cdn($url) {
        return $this->container->getParameter('ceten.websites.cdn') . $url;
    }


    /**
     * auth Twig function
     * @param string $url Oauth website url
     *
     * $url must start with a slash
     */
    public function oauth($url) {
        return $this->external($this->container->getParameter('ceten.websites.oauth')) . $url;
    }


    /**
     * external Twig function
     * Used to append /app_dev.php if debug is enabled
     * @param string $url Oauth website url
     *
     * $url must start with a slash
     */
    public function external($url) {
        return $url . ($this->isDebug() ? '/app_dev.php' : '');
    }
}
