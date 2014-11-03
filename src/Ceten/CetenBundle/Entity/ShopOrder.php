<?php

namespace Ceten\CetenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;

use Ceten\CetenBundle\Entity\User;
use Ceten\CetenBundle\Entity\ProductOrder;

/**
 * ShopOrder
 *
 * @ORM\Table(name="shop_orders")
 * @ORM\Entity
 */
class ShopOrder
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Ceten\CetenBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="Ceten\CetenBundle\Entity\ProductOrder", mappedBy="id")
     */
    private $orders;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param \Ceten\CetenBundle\Entity\User $user
     * @return ShopOrder
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Ceten\CetenBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add orders
     *
     * @param \Ceten\CetenBundle\Entity\ProductOrder $orders
     * @return ShopOrder
     */
    public function addOrder(ProductOrder $orders)
    {
        $this->orders[] = $orders;

        return $this;
    }

    /**
     * Remove orders
     *
     * @param \Ceten\CetenBundle\Entity\ProductOrder $orders
     */
    public function removeOrder(ProductOrder $orders)
    {
        $this->orders->removeElement($orders);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrders()
    {
        return $this->orders;
    }
}
