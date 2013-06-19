<?php

namespace SpiritStock\StockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * StockType
 */
class StockType
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $stock;

    /**
     * @var \SpiritStock\UserBundle\Entity\User
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->stock = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return StockType
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
     * Add stock
     *
     * @param \SpiritStock\StockBundle\Entity\Stock $stock
     * @return StockType
     */
    public function addStock(\SpiritStock\StockBundle\Entity\Stock $stock)
    {
        $this->stock[] = $stock;
    
        return $this;
    }

    /**
     * Remove stock
     *
     * @param \SpiritStock\StockBundle\Entity\Stock $stock
     */
    public function removeStock(\SpiritStock\StockBundle\Entity\Stock $stock)
    {
        $this->stock->removeElement($stock);
    }

    /**
     * Get stock
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set user
     *
     * @param \SpiritStock\UserBundle\Entity\User $user
     * @return StockType
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
     * @var integer
     */
    private $startToMinimum;

    /**
     * @var integer
     */
    private $magicToMaximum;


    /**
     * Set startToMinimum
     *
     * @param integer $startToMinimum
     * @return StockType
     */
    public function setStartToMinimum($startToMinimum)
    {
        $this->startToMinimum = $startToMinimum;
    
        return $this;
    }

    /**
     * Get startToMinimum
     *
     * @return integer 
     */
    public function getStartToMinimum()
    {
        return $this->startToMinimum;
    }

    /**
     * Set magicToMaximum
     *
     * @param integer $magicToMaximum
     * @return StockType
     */
    public function setMagicToMaximum($magicToMaximum)
    {
        $this->magicToMaximum = $magicToMaximum;
    
        return $this;
    }

    /**
     * Get magicToMaximum
     *
     * @return integer 
     */
    public function getMagicToMaximum()
    {
        return $this->magicToMaximum;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $orderHistory;


    /**
     * Add orderHistory
     *
     * @param \SpiritStock\StockBundle\Entity\OrderHistory $orderHistory
     * @return StockType
     */
    public function addOrderHistory(\SpiritStock\StockBundle\Entity\OrderHistory $orderHistory)
    {
        $this->orderHistory[] = $orderHistory;
    
        return $this;
    }

    /**
     * Remove orderHistory
     *
     * @param \SpiritStock\StockBundle\Entity\OrderHistory $orderHistory
     */
    public function removeOrderHistory(\SpiritStock\StockBundle\Entity\OrderHistory $orderHistory)
    {
        $this->orderHistory->removeElement($orderHistory);
    }

    /**
     * Get orderHistory
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrderHistory()
    {
        return $this->orderHistory;
    }
}