<?php

namespace Shop\ShopBundle\Twig;

use Twig_Extension;
use Twig_SimpleFunction;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ShopExtension extends Twig_Extension
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
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getGlobals()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        return array(
            'tags' => $em->getRepository('CetenCetenBundle:Tag')->findBy(array(), array('position' => 'ASC'))
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'shop_extension';
    }
}
