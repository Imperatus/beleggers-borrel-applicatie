<?php
/**
 * Created by PhpStorm.
 * User: jurgen
 * Date: 7/28/14
 * Time: 5:40 PM
 */

namespace SpiritStock\StockBundle\Service;

use Doctrine\ORM\EntityManager;

use SpiritStock\StockBundle\Entity\Stock;
use SpiritStock\UserBundle\Entity\User;

/**
 * Class StockService
 *
 * Contains all data manipulation on the Stock entity
 *
 * @package SpiritStock\StockBundle\Service
 */
class StockService
{
    const INCREASE = 'increase';
    const DECREASE = 'decrease';

    /** @var \Doctrine\ORM\EntityManager $em */
    private $em;

    /**
     * Constructor
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    /**
     * Update all stock items that do not qualify for increase and are not frozen
     *
     * @param array                               $stockIds
     * @param \SpiritStock\UserBundle\Entity\User $user
     */
    public function updateDecreasedStockPrice(array $stockIds, User $user) {
        /** @var Stock[] $notOrdered */
        $notOrdered = $this->em->getRepository('SpiritStockStockBundle:Stock')->inverseFindToBeUpdatedByIdsAndUser($stockIds, $user);

        foreach($notOrdered as $stock) {
            $currentPrice = $stock->getCurrentPrice();
            $minPrice     = $stock->getMinPrice();
            $voodoo       = $this->calculateDecrease($stock); // I'm sorry for the naming... But it is voodoo!
            $newPrice     = $currentPrice - $voodoo;

            // Make sure we don't get under the minimum price
            if ($newPrice < $minPrice) {
                $newPrice = $minPrice;
            }

            $stock->setCurrentPrice($newPrice);
            $stock->setChangeType(self::DECREASE);
            $stock->setPriceChange('- '.number_format($voodoo,2));

            $this->em->persist($stock);
        }
    }

    /**
     * Set new stock amount and persist the entity
     *
     * @param Stock $stock
     * @param $amount
     *
     * @return bool TODO - Kinda useless right now, keeping this if we need to extend
     */
    public function updateStockAmount(Stock $stock, $amount) {
        $current = $stock->getCurrentStock();
        $new     = $current - $amount;

        // If amount drops to 0, keep it at zero. This might be the case when initial stock was set too low
        // We still want the system to work in that scenario though!
        if ($new < 0) {
            $new = 0;
        }

        $stock->setCurrentStock($new);
        $this->em->persist($stock);

        return true;
    }

    /**
     * Updates the increased stock price. DOES NOT FLUSH! ONLY FLUSHES IF ALL OTHER FUNCTION CALLS SUCCEED!!!
     *
     * @param Stock $stock The Stock entity
     */
    public function updateIncreasedStockPrice(Stock $stock) {
        $currentPrice = $stock->getCurrentPrice();
        $maxPrice     = $stock->getMaxPrice();
        $voodoo       = $this->calculateIncrease($stock);
        $newPrice     = $currentPrice + $voodoo;

        if($newPrice > $maxPrice) {
            $newPrice = $maxPrice;
        }

        $stock->setCurrentPrice($newPrice);
        $stock->setChangeType(self::INCREASE);
        $stock->setPriceChange('+ '.number_format($voodoo,2));

        $this->em->persist($stock);
    }

    /**
     * Calculates the amount a stock item's price should increase with the help of black magic
     *
     * @param Stock $stock Stock item entity
     *
     * @return int The amount a stock item should increase!
     */
    private function calculateIncrease(Stock $stock) {
        $type            = $stock->getStockType();
        $currentPrice    = $stock->getCurrentPrice();
        $startingPrice   = $stock->getStartingPrice();
        $startingStock   = $stock->getStartingStock();
        $currentStock    = $stock->getCurrentStock();
        $maxPrice        = $stock->getMaxPrice();
        $magicNumber     = $type->getMagicToMaximum();

        // Calculate number between 1 and 0 for stock amount. Inverse as you want prices to rise quicker when there's less stock
        $stockMultiplier = 1 - ($currentStock / $startingStock);
        $priceMultiplier = 1 - ($currentPrice / $maxPrice);
        $multiplier      = $magicNumber * $stockMultiplier * $priceMultiplier;
        $voodoo          = $multiplier * $startingPrice * 4;

        return $voodoo;
    }

    /**
     * Calculates the amount a stock item's price should decrease with the help of black magic
     * As you can see, this is a totally different calculation from the increase, so warrants its own function
     *
     * @param Stock $stock The Stock entity
     *
     * @return float|int
     */
    private function calculateDecrease(Stock $stock) {
        $type = $stock->getStockType();

        /* Not used for current alg.
            $currentPrice    = $stock->getCurrentPrice();
            $startingStock   = $stock->getStartingStock();
            $currentStock    = $stock->getCurrentStock();
        */

        $startingPrice   = $stock->getStartingPrice();
        $minPrice        = $stock->getMinPrice();
        $updated         = $stock->getUpdated();
        $timeToMin       = $type->getStartToMinimum();
        $priceDifference = $startingPrice - $minPrice;

        $now      = new \DateTime();
        $now      = strtotime($now->format('Y-m-d H:i:s'));
        $updated  = strtotime($updated->format('Y-m-d H:i:s'));

        $interval = $now - $updated;
        $interval = round(abs($interval/60));

        if($interval > 5) {
            $divider = $timeToMin / $interval;
            $voodoo  = $priceDifference / $divider;
        } else {
            $voodoo  = 0;
        }

        return $voodoo;
    }

} 