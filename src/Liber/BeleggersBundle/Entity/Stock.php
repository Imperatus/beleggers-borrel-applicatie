<?php

namespace Liber\BeleggersBundle\Entity;

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
     * @var \Liber\BeleggersBundle\Entity\StockType
     */
    private $stockTypes;


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
     * Set stockTypes
     *
     * @param \Liber\BeleggersBundle\Entity\StockType $stockTypes
     * @return Stock
     */
    public function setStockTypes(\Liber\BeleggersBundle\Entity\StockType $stockTypes = null)
    {
        $this->stockTypes = $stockTypes;

        return $this;
    }

    /**
     * Get stockTypes
     *
     * @return \Liber\BeleggersBundle\Entity\StockType 
     */
    public function getStockTypes()
    {
        return $this->stockTypes;
    }
}
