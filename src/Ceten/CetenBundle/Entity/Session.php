<?php

namespace Ceten\CetenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Session
 *
 * @ORM\Table(name="sessions", uniqueConstraints={@ORM\UniqueConstraint(name="session_unique_idx", columns={"name"})})
 * @ORM\Entity(repositoryClass="Ceten\CetenBundle\Entity\SessionRepository")
 */
class Session
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=40)
     */
    private $name;

      /**
       * @ORM\ManyToOne(targetEntity="Ceten\CetenBundle\Entity\User", cascade={"persist"}, fetch="EAGER")
       * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
       */
    private $user;


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
     * @return Session
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
     * Set user
     *
     * @param \Ceten\CetenBundle\Entity\User $user
     * @return Session
     */
    public function setUser(\Ceten\CetenBundle\Entity\User $user = null)
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
}
