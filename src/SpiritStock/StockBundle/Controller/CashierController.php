<?php
namespace SpiritStock\StockBundle\Controller;

use SpiritStock\StockBundle\Entity\OrderHistory;
use SpiritStock\StockBundle\Entity\Stock;
use SpiritStock\StockBundle\Controller\LocaleController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Collection;

class CashierController extends LocaleController {

    const INCREASE = 'increase';
    const DECREASE = 'decrease';

    public function orderAction() {
        $types = $this->em->getRepository('SpiritStockStockBundle:StockType')->findByUser($this->user);
        $stock = $this->em->getRepository('SpiritStockStockBundle:Stock')->findByUser($this->user);

        $settings = $this->em->getRepository('SpiritStockStockBundle:GlobalSettings')->findOneByUser($this->user);

        return $this->render('SpiritStockStockBundle:Cashier:order.html.twig', array(
            'settings'  => $settings,
            'types'     => $types,
            'stock'     => $stock,
        ));
    }

    public function handleOrderAction() {
        $request = $this->getRequest();

        if($request->getMethod() === 'POST') {
            $stocks = $request->request->all();

            $stockIds = array();

            foreach($stocks as $stockId => $amount) {
                $stock = $this->em->getRepository('SpiritStockStockBundle:Stock')->findOneById($stockId);

                array_push($stockIds, $stockId);

                if($this->updateStockAmount($stock, $amount)) {
                    $this->updateIncreasedStockPrice($stock);
                } else {
                    //SCREAM!!!
                }
            }
            $this->updateDecreasedStockPrice($stockIds);

            // Update history for all stock items
            $this->updateHistory();

            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('form.order.success'));
            $this->em->flush();
        }

        $url = $this->generateUrl('spiritstock_stock_cashier_register');
        return $this->redirect($url);
    }

    private function calculateIncrease(Stock $stock) {
        $type = $stock->getStockType();

        $currentPrice = $stock->getCurrentPrice();
        $startingPrice = $stock->getStartingPrice();
        $startingStock = $stock->getStartingStock();
        $currentStock = $stock->getCurrentStock();
        $maxPrice = $stock->getMaxPrice();

        $magicNumber = $type->getMagicToMaximum();

        // Calculate number between 1 and 0 for stock amount. Inverse as you want prices to rise quicker when there's less stock
        $stockMultiplier = 1 - ($currentStock / $startingStock);
        $priceMultiplier =  1 - ($currentPrice / $maxPrice);
        $multiplier = $magicNumber * $stockMultiplier * $priceMultiplier;

        $voodoo = $multiplier * $startingPrice * 4;

        return $voodoo;
    }

    private function updateIncreasedStockPrice(Stock $stock) {
        $currentPrice = $stock->getCurrentPrice();
        $maxPrice = $stock->getMaxPrice();

        $voodoo = $this->calculateIncrease($stock);

        $newPrice = $currentPrice + $voodoo;

        if($newPrice > $maxPrice) {
            $newPrice = $maxPrice;
        }

        $stock->setCurrentPrice($newPrice);
        $stock->setChangeType(self::INCREASE);
        $stock->setPriceChange('+ '.number_format($voodoo,2));

        $this->em->persist($stock);
    }

    private function calculateDecrease(Stock $stock) {
        $type = $stock->getStockType();

        /* Not used for current alg. */
        $currentPrice = $stock->getCurrentPrice();
        $startingStock = $stock->getStartingStock();
        $currentStock = $stock->getCurrentStock();

        $startingPrice = $stock->getStartingPrice();
        $minPrice = $stock->getMinPrice();
        $updated = $stock->getUpdated();

        $timeToMin = $type->getStartToMinimum();

        $priceDifference = $startingPrice - $minPrice;

        $now = new \DateTime();
        $now = strtotime($now->format('Y-m-d H:i:s'));
        $updated = strtotime($updated->format('Y-m-d H:i:s'));

        $interval = $now - $updated;
        $interval = round(abs($interval/60));

        if($interval > 5) {
            $divider = $timeToMin / $interval;
            $voodoo = $priceDifference / $divider;
        } else {
            $voodoo = 0;
        }

        return $voodoo;
    }

    private function updateDecreasedStockPrice($stockIds) {
        $notOrdered = $this->em->getRepository('SpiritStockStockBundle:Stock')->inverseFindToBeUpdatedByIdsAndUser($stockIds, $this->user);

        /** @var Stock $stock */
        foreach($notOrdered as $stock) {
            $currentPrice = $stock->getCurrentPrice();
            $minPrice = $stock->getMinPrice();

            $voodoo = $this->calculateDecrease($stock);

            $newPrice = $currentPrice - $voodoo;

            if($newPrice < $minPrice) {
                $newPrice = $minPrice;
            }

            $stock->setCurrentPrice($newPrice);
            $stock->setChangeType(self::DECREASE);
            $stock->setPriceChange('- '.number_format($voodoo,2));
            $this->em->persist($stock);
        }

    }

    /**
     * @param Stock $stock
     * @param $amount
     * @return bool
     */
    private function updateStockAmount(Stock $stock, $amount) {
        $current = $stock->getCurrentStock();
        $new = $current - $amount;

        if($new < 0) {
            $new = 0;
        }

        $stock->setCurrentStock($new);

        $this->em->persist($stock);
        return true;
    }

    private function updateHistory() {
        $stock = $this->em->getRepository('SpiritStockStockBundle:Stock')->findByUser($this->user);
        $now = new \DateTime();

        /** @var Stock $item */
        foreach($stock as $item) {
            $history = new OrderHistory();

            $history->setStock($item);
            $history->setUser($this->user);
            $history->setPrice($item->getCurrentPrice());
            $history->setTime($now);

            $history->setChangeType($item->getChangeType());

            $this->em->persist($history);
        }
    }
}