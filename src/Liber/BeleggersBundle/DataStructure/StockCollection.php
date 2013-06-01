<?php

namespace Liber\BeleggersBundle\DataStructure;

class StockCollection {
    private $stocks;

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
}