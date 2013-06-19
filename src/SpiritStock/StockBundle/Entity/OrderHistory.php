<?php

namespace SpiritStock\StockBundle\Entity;

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
     * @var \SpiritStock\StockBundle\Entity\Stock
     */
    private $stock;

    /**
     * @var \SpiritStock\UserBundle\Entity\User
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
     * @param \SpiritStock\StockBundle\Entity\Stock $stock
     * @return OrderHistory
     */
    public function setStock(\SpiritStock\StockBundle\Entity\Stock $stock = null)
    {
        $this->stock = $stock;
    
        return $this;
    }

    /**
     * Get stock
     *
     * @return \SpiritStock\StockBundle\Entity\Stock
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set user
     *
     * @param \SpiritStock\UserBundle\Entity\User $user
     * @return OrderHistory
     */
    public function setUser(\SpiritStock\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \SpiritStock\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @var float
     */
    private $increase;


    /**
     * Set increase
     *
     * @param float $increase
     * @return OrderHistory
     */
    public function setIncrease($increase)
    {
        $this->increase = $increase;
    
        return $this;
    }

    /**
     * Get increase
     *
     * @return float 
     */
    public function getIncrease()
    {
        return $this->increase;
    }
    /**
     * @var string
     */
    private $type;


    /**
     * Set type
     *
     * @param string $type
     * @return OrderHistory
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * @var string
     */
    private $changeType;


    /**
     * Set changeType
     *
     * @param string $changeType
     * @return OrderHistory
     */
    public function setChangeType($changeType)
    {
        $this->changeType = $changeType;
    
        return $this;
    }

    /**
     * Get changeType
     *
     * @return string 
     */
    public function getChangeType()
    {
        return $this->changeType;
    }
}