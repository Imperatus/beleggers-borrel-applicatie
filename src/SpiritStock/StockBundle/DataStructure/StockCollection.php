<?php

namespace SpiritStock\StockBundle\DataStructure;

class StockCollection {
    private $stocks;
    private $stockTypes;

    /**
     * @return mixed
     */
    public function getStocks()
    {
        return $this->stocks;
    }

    /**
     * @param $stocks
     * @return mixed
     */
    public function setStocks($stocks)
    {
        $this->stocks = $stocks;
        return $this->stocks;
    }

    /**
     * @return mixed
     */
    public function getStockTypes()
    {
        return $this->stockTypes;
    }

    /**
     * @param $stocks
     * @return mixed
     */
    public function setStockTypes($stockTypes)
    {
        $this->stockTypes = $stockTypes;
        return $this->stockTypes;
    }
}