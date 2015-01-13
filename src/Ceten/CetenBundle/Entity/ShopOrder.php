<?php

namespace Ceten\CetenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

use Doctrine\Common\Collections\ArrayCollection;

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
     * @Serializer\Groups({ "order_detail" })
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Ceten\CetenBundle\Entity\User", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     * @Serializer\Exclude()
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="Ceten\CetenBundle\Entity\ProductOrder", mappedBy="order", cascade={"persist"}, fetch="EAGER")
     * @Assert\NotBlank()
     * @Assert\Count(min=1)
     * @Serializer\Groups({ "order_detail" })
     */
    private $orders;

    /**
     * @var integer
     * { 0: Not paid yet, 1: Credit card, 2: Cash, 3: Check, 4: Wire transfer }
     *
     * @ORM\Column(name="payment", type="integer", options={"default" = 0})
     * @Assert\NotBlank()
     * @Assert\Choice(choices = {0, 1, 2, 3, 4})
     * @Serializer\Groups({ "order_list" })
     */
    private $payment = 0;

    /**
     * @var integer
     * { 0: order waiting for , 1: withdraw possible, 2: withdrawn, 3: sent }
     *
     * @ORM\Column(name="state", type="integer", options={"default" = 0})
     * @Assert\NotBlank()
     * @Assert\Choice(choices = {0, 1, 2, 3})
     * @Serializer\Groups({ "order_list" })
     */
    private $state = 0;

    /**
     * @var Datetime
     *
     * @ORM\Column(name="created", type="datetime")
     * @Serializer\Groups({ "order_list" })
     */
    private $created;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float")
     * @Assert\NotBlank()
     * @Assert\Type(type="float")
     * @Serializer\Groups({ "order_list" })
     */
    private $total;

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
        $orders->setOrder($this);
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

    /**
     * Set payment
     *
     * @param integer $payment
     * @return ShopOrder
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * Get payment
     *
     * @return integer
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * Set state
     *
     * @param integer $state
     * @return ShopOrder
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return integer 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return ShopOrder
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set total
     *
     * @param float $total
     * @return ShopOrder
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float 
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * 
     */
    public function calculateTotal()
    {
        $total = 0;
        foreach ($this->orders as $order) {
            $product = $order->getProduct();

            $price = $product->getPrice();
            if ($this->user->getCeten()) {
                $price = $product->getCetenPrice();
            }

            $price *= $order->getCount();

            $total += $price;
        }

        $this->total = $total;
    }

    public function __tostring()
    {
        return $this->user->__tostring() . ' (' . date('d/m/Y à H:i:s', $this->created->getTimestamp()) . ')';
    }
}
