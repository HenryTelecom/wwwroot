<?php

namespace Ceten\CetenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Product
 *
 * @ORM\Table(name="products", uniqueConstraints={@ORM\UniqueConstraint(name="product_unique_idx", columns={"slug"})})
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class Product
{

    const CDN_DIR = '/cdn.ceten.fr';
    const IMAGE_PATH = '/uploads/shop/products';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", length=100)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     * @Assert\NotBlank()
     * @Assert\Type(type="float")
     */
    private $price;

    /**
     * @var float
     *
     * @ORM\Column(name="priceCeten", type="float")
     * @Assert\NotBlank()
     * @Assert\Type(type="float")
     */
    private $priceCeten;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Ceten\CetenBundle\Entity\Tag", inversedBy="products", fetch="EAGER", cascade={"persist"})
     * @ORM\JoinTable(name="products_tags")
     * @Assert\Count(min=1)
     */
    private $tags;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;

    /**
     * Image uplaod handler
     * @var UploadedFile
     */
    private $imageFile;
    private $temp;
    private $tempName;


    public function __construct()
    {
        $this->tags = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Product
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set priceCeten
     *
     * @param float $priceCeten
     * @return Product
     */
    public function setPriceCeten($priceCeten)
    {
        $this->priceCeten = $priceCeten;

        return $this;
    }

    /**
     * Get priceCeten
     *
     * @return float 
     */
    public function getPriceCeten()
    {
        return $this->priceCeten;
    }

    /**
     * Add tags
     *
     * @param \Ceten\CetenBundle\Entity\Tag $tags
     * @return Product
     */
    public function addTag(\Ceten\CetenBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param \Ceten\CetenBundle\Entity\Tag $tags
     */
    public function removeTag(\Ceten\CetenBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Product
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Product
     */
    public function setUpdated(\DateTime $updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    public function setImageFile(UploadedFile $imageFile = null)
    {
        $this->imageFile = $imageFile;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }


    public function refreshUpdated() {
        $this->setUpdated(new \DateTime("now"));
    }

    /**
     * Upload file
     *
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null === $this->imageFile) {
            return;
        }

        $this->temp = $this->image;
        $this->tempName = sha1(uniqid(mt_rand(), true)) . '.' .  $this->imageFile->guessClientExtension();
        $this->image =  self::IMAGE_PATH . '/' . $this->tempName;
    }

    /**
     * Upload file
     *
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->imageFile) {
            return;
        }
        $dir = \CETEN_ROOT . self::CDN_DIR;

        $this->imageFile->move(
            $dir . self::IMAGE_PATH,
            $this->tempName
        );

        if (!empty($this->temp) && is_file($dir . $this->temp)) {
            unlink($dir . $this->temp);
            $this->temp = null;
        }

        $this->imageFile = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        $file = \CETEN_ROOT . self::CDN_DIR . $this->image;
        if (!empty($this->image) && is_file($file)) {
            unlink($file);
        }
    }

    public function __tostring()
    {
        return $this->name;
    }
}
