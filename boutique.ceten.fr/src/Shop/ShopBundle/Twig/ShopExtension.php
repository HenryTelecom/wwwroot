<?php

namespace Shop\ShopBundle\Twig;

use Twig_Extension;
use Twig_SimpleFunction;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Ceten\CetenBundle\Entity\User;

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
            new Twig_SimpleFunction('price', array($this, 'price'))
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getGlobals()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        return array(
            'tags' => $em->getRepository('CetenCetenBundle:Tag')->findBy(array(), array('position' => 'ASC')),
            'cartCookie' => $this->container->getParameter('shop.shop_cart'),
            'maxOrder' => $this->container->getParameter('shop.max_order'),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'shop_extension';
    }


    /**
     * Get price for a user
     * @param  [type] $user    [description]
     * @param  [type] $product [description]
     * @return float
     */
    public function price($user, $product)
    {
        if ($user instanceof User && $user->getCeten()) {
            return $product->getCetenPrice();
        }
        return $product->getPrice();
    }
}
