<?php

namespace Liber\BeleggersBundle\Entity;

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
     * @var \Liber\UserBundle\Entity\User
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
     * @param \Liber\BeleggersBundle\Entity\Stock $stock
     * @return StockType
     */
    public function addStock(\Liber\BeleggersBundle\Entity\Stock $stock)
    {
        $this->stock[] = $stock;
    
        return $this;
    }

    /**
     * Remove stock
     *
     * @param \Liber\BeleggersBundle\Entity\Stock $stock
     */
    public function removeStock(\Liber\BeleggersBundle\Entity\Stock $stock)
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
     * @param \Liber\UserBundle\Entity\User $user
     * @return StockType
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
     * @param \Liber\BeleggersBundle\Entity\OrderHistory $orderHistory
     * @return StockType
     */
    public function addOrderHistory(\Liber\BeleggersBundle\Entity\OrderHistory $orderHistory)
    {
        $this->orderHistory[] = $orderHistory;
    
        return $this;
    }

    /**
     * Remove orderHistory
     *
     * @param \Liber\BeleggersBundle\Entity\OrderHistory $orderHistory
     */
    public function removeOrderHistory(\Liber\BeleggersBundle\Entity\OrderHistory $orderHistory)
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