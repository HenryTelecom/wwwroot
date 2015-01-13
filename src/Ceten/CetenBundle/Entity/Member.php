<?php

namespace Ceten\CetenBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Member
 *
 * @ORM\Table(name="members")
 * @ORM\Entity
 */
class Member
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
     * @ORM\Column(name="firstname", type="string", length=100)
     * @Assert\NotBlank()
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=100)
     * @Assert\NotBlank()
     */
    private $lastname;

    /**
     * @var integer
     *
     * @ORM\Column(name="year", type="integer")
     * @Assert\NotBlank()
     * @Assert\Choice(choices = {1, 2, 3, 4})
     */
    private $year;

    /**
     * @var integer
     *
     * @ORM\Column(name="payment", type="integer")
     * @Assert\NotBlank()
     * @Assert\Choice(choices = {0, 1, 2, 3, 4, 5, 6})
     */
    private $payment;

    /**
     * @var boolean
     *
     * @ORM\Column(name="deposit", type="boolean")
     */
    private $deposit;

    /**
     * @var string
     *
     * @ORM\Column(name="depositName", type="string", length=200, nullable=true)
     */
    private $depositName;

    /**
     * @var boolean
     *
     * @ORM\Column(name="welcomePack", type="boolean")
     */
    private $welcomePack;

    /**
     * @var boolean
     *
     * @ORM\Column(name="mailing", type="boolean")
     */
    private $mailing;

    /**
     * @var string
     * 
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;


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
     * Set firstname
     *
     * @param string $firstname
     * @return Member
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return Member
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set year
     *
     * @param integer $year
     * @return Member
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer 
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set payment
     *
     * @param integer $payment
     * @return Member
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
     * Set deposit
     *
     * @param boolean $deposit
     * @return Member
     */
    public function setDeposit($deposit)
    {
        $this->deposit = $deposit;

        return $this;
    }

    /**
     * Get deposit
     *
     * @return boolean 
     */
    public function getDeposit()
    {
        return $this->deposit;
    }

    /**
     * Set depositName
     *
     * @param string $depositName
     * @return Member
     */
    public function setDepositName($depositName)
    {
        $this->depositName = $depositName;

        return $this;
    }

    /**
     * Get depositName
     *
     * @return string 
     */
    public function getDepositName()
    {
        return $this->depositName;
    }

    /**
     * Set welcomePack
     *
     * @param boolean $welcomePack
     * @return Member
     */
    public function setWelcomePack($welcomePack)
    {
        $this->welcomePack = $welcomePack;

        return $this;
    }

    /**
     * Get welcomePack
     *
     * @return boolean 
     */
    public function getWelcomePack()
    {
        return $this->welcomePack;
    }

    /**
     * Set mailing
     *
     * @param boolean $mailing
     * @return Member
     */
    public function setMailing($mailing)
    {
        $this->mailing = $mailing;

        return $this;
    }

    /**
     * Get mailing
     *
     * @return boolean 
     */
    public function getMailing()
    {
        return $this->mailing;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Member
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    public function __tostring()
    {
        return $this->firstname . ' ' . $this->lastname;
    }
}
