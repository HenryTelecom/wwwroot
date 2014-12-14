<?php

namespace Ceten\CetenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Club
 *
 * @ORM\Table(name="clubs")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class Club
{
    const CDN_DIR = '/cdn.ceten.fr/web';
    const IMAGE_PATH = '/uploads/clubs';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({ "club_list", "club_detail" })
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     * @Serializer\Groups({ "club_list", "club_detail" })
     */
    private $name;
    
    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", length=100)
     * @Serializer\Groups({ "club_list", "club_detail" })
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     * @Serializer\Groups({ "club_list", "club_detail" })
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     * @Serializer\Groups({ "club_list", "club_detail" })
     */
    private $logo;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer")
     * @Serializer\Groups({ "club_detail" })
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=255, nullable=true)
     * @Serializer\Groups({ "club_list", "club_detail" })
     */
    private $website;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updated", type="datetime")
     * @Serializer\Exclude()
     */
    private $updated;

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
     * Set name
     *
     * @param string $name
     * @return Club
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
     * Set description
     *
     * @param string $description
     * @return Club
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
     * Set logo
     *
     * @param string $logo
     * @return Club
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string 
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Club
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set website
     *
     * @param string $website
     * @return Club
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string 
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Club
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

        $this->temp = $this->logo;
        $this->tempName = sha1(uniqid(mt_rand(), true)) . '.' .  $ext;
        $this->logo =  self::IMAGE_PATH . '/' . $this->tempName;
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
        $file = \CETEN_ROOT . self::CDN_DIR . $this->logo;
        if (!empty($this->logo) && is_file($file)) {
            unlink($file);
        }
    }

    public function __tostring()
    {
        return $this->name;
    }
}
