<?php

namespace SpiritStock\StockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stock
 */
class Stock
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
     * @var float
     */
    private $startingPrice;

    /**
     * @var float
     */
    private $currentPrice;

    /**
     * @var float
     */
    private $maxPrice;

    /**
     * @var float
     */
    private $minPrice;

    /**
     * @var integer
     */
    private $startingStock;

    /**
     * @var integer
     */
    private $currentStock;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * @var \SpiritStock\StockBundle\Entity\StockType
     */
    private $stockType;

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
     * Set name
     *
     * @param string $name
     * @return Stock
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
     * Set startingPrice
     *
     * @param float $startingPrice
     * @return Stock
     */
    public function setStartingPrice($startingPrice)
    {
        $this->startingPrice = $startingPrice;
    
        return $this;
    }

    /**
     * Get startingPrice
     *
     * @return float 
     */
    public function getStartingPrice()
    {
        return $this->startingPrice;
    }

    /**
     * Set currentPrice
     *
     * @param float $currentPrice
     * @return Stock
     */
    public function setCurrentPrice($currentPrice)
    {
        $this->currentPrice = $currentPrice;
    
        return $this;
    }

    /**
     * Get currentPrice
     *
     * @return float 
     */
    public function getCurrentPrice()
    {
        return $this->currentPrice;
    }

    /**
     * Set maxPrice
     *
     * @param float $maxPrice
     * @return Stock
     */
    public function setMaxPrice($maxPrice)
    {
        $this->maxPrice = $maxPrice;
    
        return $this;
    }

    /**
     * Get maxPrice
     *
     * @return float 
     */
    public function getMaxPrice()
    {
        return $this->maxPrice;
    }

    /**
     * Set minPrice
     *
     * @param float $minPrice
     * @return Stock
     */
    public function setMinPrice($minPrice)
    {
        $this->minPrice = $minPrice;
    
        return $this;
    }

    /**
     * Get minPrice
     *
     * @return float 
     */
    public function getMinPrice()
    {
        return $this->minPrice;
    }

    /**
     * Set startingStock
     *
     * @param integer $startingStock
     * @return Stock
     */
    public function setStartingStock($startingStock)
    {
        $this->startingStock = $startingStock;
    
        return $this;
    }

    /**
     * Get startingStock
     *
     * @return integer 
     */
    public function getStartingStock()
    {
        return $this->startingStock;
    }

    /**
     * Set currentStock
     *
     * @param integer $currentStock
     * @return Stock
     */
    public function setCurrentStock($currentStock)
    {
        $this->currentStock = $currentStock;
    
        return $this;
    }

    /**
     * Get currentStock
     *
     * @return integer 
     */
    public function getCurrentStock()
    {
        return $this->currentStock;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Stock
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
     * Set updated
     *
     * @param \DateTime $updated
     * @return Stock
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
     * Set stockType
     *
     * @param \SpiritStock\StockBundle\Entity\StockType $stockType
     * @return Stock
     */
    public function setStockType(\SpiritStock\StockBundle\Entity\StockType $stockType = null)
    {
        $this->stockType = $stockType;
    
        return $this;
    }

    /**
     * Get stockType
     *
     * @return \SpiritStock\StockBundle\Entity\StockType
     */
    public function getStockType()
    {
        return $this->stockType;
    }

    /**
     * Set user
     *
     * @param \SpiritStock\UserBundle\Entity\User $user
     * @return Stock
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
     * @ORM\PrePersist
     */
    public function setInitialTimestamp()
    {
        $this->created = $this->updated = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedValue()
    {
        $this->updated = new \DateTime();
    }
    /**
     * @var \SpiritStock\StockBundle\Entity\OrderHistory
     */
    private $orderHistory;


    /**
     * Set orderHistory
     *
     * @param \SpiritStock\StockBundle\Entity\OrderHistory $orderHistory
     * @return Stock
     */
    public function setOrderHistory(\SpiritStock\StockBundle\Entity\OrderHistory $orderHistory = null)
    {
        $this->orderHistory = $orderHistory;
    
        return $this;
    }

    /**
     * Get orderHistory
     *
     * @return \SpiritStock\StockBundle\Entity\OrderHistory
     */
    public function getOrderHistory()
    {
        return $this->orderHistory;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->orderHistory = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add orderHistory
     *
     * @param SpiritStock\StockBundle\Entity\OrderHistory $orderHistory
     * @return Stock
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
}