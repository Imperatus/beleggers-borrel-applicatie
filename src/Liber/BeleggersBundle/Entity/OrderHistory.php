<?php

namespace Liber\BeleggersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderHistory
 */
class OrderHistory
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var float
     */
    private $price;

    /**
     * @var integer
     */
    private $amount;

    /**
     * @var \DateTime
     */
    private $time;

    /**
     * @var \Liber\BeleggersBundle\Entity\Stock
     */
    private $stock;

    /**
     * @var \Liber\UserBundle\Entity\User
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
     * Set price
     *
     * @param float $price
     * @return OrderHistory
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
     * Set amount
     *
     * @param integer $amount
     * @return OrderHistory
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    
        return $this;
    }

    /**
     * Get amount
     *
     * @return integer 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     * @return OrderHistory
     */
    public function setTime($time)
    {
        $this->time = $time;
    
        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set stock
     *
     * @param \Liber\BeleggersBundle\Entity\Stock $stock
     * @return OrderHistory
     */
    public function setStock(\Liber\BeleggersBundle\Entity\Stock $stock = null)
    {
        $this->stock = $stock;
    
        return $this;
    }

    /**
     * Get stock
     *
     * @return \Liber\BeleggersBundle\Entity\Stock 
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set user
     *
     * @param \Liber\UserBundle\Entity\User $user
     * @return OrderHistory
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
}
