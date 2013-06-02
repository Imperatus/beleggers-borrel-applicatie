<?php

namespace Liber\BeleggersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GlobalSettings
 */
class GlobalSettings
{
    /**
     * @var integer
     */
    private $id;


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
     * @var string
     */
    private $currency;

    /**
     * @var float
     */
    private $unitPrice;

    /**
     * @var \Liber\UserBundle\Entity\User
     */
    private $user;


    /**
     * Set currency
     *
     * @param string $currency
     * @return GlobalSettings
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    
        return $this;
    }

    /**
     * Get currency
     *
     * @return string 
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set unitPrice
     *
     * @param float $unitPrice
     * @return GlobalSettings
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;
    
        return $this;
    }

    /**
     * Get unitPrice
     *
     * @return float 
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * Set user
     *
     * @param \Liber\UserBundle\Entity\User $user
     * @return GlobalSettings
     */
    public function setUser(\Liber\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Liber\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @var string
     */
    private $unitName;


    /**
     * Set unitName
     *
     * @param string $unitName
     * @return GlobalSettings
     */
    public function setUnitName($unitName)
    {
        $this->unitName = $unitName;
    
        return $this;
    }

    /**
     * Get unitName
     *
     * @return string 
     */
    public function getUnitName()
    {
        return $this->unitName;
    }
}