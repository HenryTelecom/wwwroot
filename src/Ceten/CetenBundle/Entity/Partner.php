<?php

namespace Ceten\CetenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Partner
 *
 * @ORM\Table(name="partners")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class Partner
{
    const CDN_DIR = '/cdn.ceten.fr/web';
    const IMAGE_PATH = '/uploads/partners';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({ "partner_list" })
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     * @Assert\NotBlank()
     * @Serializer\Groups({ "partner_list" })
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     * @Serializer\Groups({ "partner_list" })
     */
    private $logo;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=255)
     * @Serializer\Groups({ "partner_list" })
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
