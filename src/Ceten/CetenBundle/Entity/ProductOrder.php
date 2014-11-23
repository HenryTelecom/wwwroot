<?php

namespace Ceten\CetenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

use Ceten\CetenBundle\Entity\Product;
use Ceten\CetenBundle\Entity\ShopOrder;

/**
 * ProductOrder
 *
 * @ORM\Table(name="products_orders")
 * @ORM\Entity
 */
class ProductOrder
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
     * @var integer
     *
     * @ORM\Column(name="count", type="integer")
     * @Serializer\Groups({ "order_detail" })
     */
    private $count;


    /**
     * @var Product
     * 
     * @ORM\ManyToOne(targetEntity="Ceten\CetenBundle\Entity\Product", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({ "order_detail" })
     */
    private $product;

    /**
     * @var ShopOrder
     * 
     * @ORM\ManyToOne(targetEntity="Ceten\CetenBundle\Entity\ShopOrder", inversedBy="orders", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false, onDelete="cascade")
     * @Serializer\Exclude()
     */
    private $order;


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
     * Set count
     *
     * @param integer $count
     * @return ProductOrder
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer 
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set product
     *
     * @param Product $product
     * @return ProductOrder
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Ceten\CetenBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set order
     *
     * @param \Ceten\CetenBundle\Entity\ShopOrder $order
     * @return ProductOrder
     */
    public function setOrder(ShopOrder $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \Ceten\CetenBundle\Entity\ShopOrder 
     */
    public function getOrder()
    {
        return $this->order;
    }

    public function __tostring()
    {
        return $this->product->__tostring() . ' (' . $this->count . ')';
    }
}
