<?php

namespace Ceten\CetenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use DateTime;

/**
 * News
 *
 * @ORM\Table(name="news")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class News
{
    const CDN_DIR = '/cdn.ceten.fr/web';
    const IMAGE_PATH = '/uploads/news';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({ "news_list" })
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100)
     * @Serializer\Groups({ "news_list" })
     */
    private $title;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", length=100)
     * @Serializer\Groups({ "news_list" })
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     * @Serializer\Groups({ "news_list" })
     */
    private $image;


    /**
     * @var string
     *
     * @ORM\Column(name="overview", type="string", length=255)
     * @Serializer\Groups({ "news_list" })
     */
    private $overview;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updated", type="datetime")
     * @Serializer\Exclude()
     */
    private $updated;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     * @Serializer\Exclude()
     */
    private $created;

    /**
     * Image uplaod handler
     * @var UploadedFile
     */
    private $imageFile;
    private $temp;
    private $tempName;

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
     * Set title
     *
     * @param string $title
     * @return News
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return News
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
     * Set image
     *
     * @param string $image
     * @return News
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
     * Set overview
     *
     * @param string $overview
     * @return News
     */
    public function setOverview($overview)
    {
        $this->overview = $overview;

        return $this;
    }

    /**
     * Get overview
     *
     * @return string 
     */
    public function getOverview()
    {
        return $this->overview;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Club
     */
    public function setUpdated($updated)
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

    
    /**
     * Set created
     *
     * @param \DateTime $created
     * @return News
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
     * Set file.
     * 
     * @param   $imagFile
     */
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

        $ext = $this->imageFile->guessClientExtension();
        if (!in_array($ext, array('png', 'jpg', 'jpeg'))) {
            return;
        }

        $this->temp = $this->image;
        $this->tempName = sha1(uniqid(mt_rand(), true)) . '.' .  $ext;
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

    /**roduc
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
        return $this->title;
    }
}
