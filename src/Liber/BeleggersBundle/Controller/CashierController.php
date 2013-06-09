<?php
namespace Liber\BeleggersBundle\Controller;

use Liber\BeleggersBundle\Entity\OrderHistory;
use Liber\BeleggersBundle\Entity\Stock;
use Liber\BeleggersBundle\Controller\LocaleController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Collection;

class CashierController extends LocaleController {

    const INCREASE = 'increase';
    const DECREASE = 'decrease';

    public function orderAction() {
        $settings = $this->em->getRepository('LiberBeleggersBundle:GlobalSettings')->findOneByUser($this->user);
        $types = $this->em->getRepository('LiberBeleggersBundle:StockType')->findByUser($this->user);

        return $this->render('LiberBeleggersBundle:Cashier:order.html.twig', array(
            'settings' => $settings,
            'types' => $types,
        ));
    }

    public function handleOrderAction() {
        $now = new \DateTime();

        $request = $this->getRequest();
        if($request->getMethod() === 'POST') {
            $stocks = $request->request->all();

            $stockIds = array();

            foreach($stocks as $stockId => $amount) {
                $stock = $this->em->getRepository('LiberBeleggersBundle:Stock')->findOneById($stockId);

                array_push($stockIds, $stockId);

                if($this->updateStockAmount($stock, $amount)) {
                    $this->updateHistory($stock, $amount, $now);
                    $this->updateIncreasedStockPrice($stock);

                } else {
                    //SCREAM!!!
                }
            }
            $this->updateDecreasedStockPrice($stockIds);
            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('form.order.success'));
            $this->em->flush();
        }

        $url = $this->generateUrl('liber_beleggers_cashier_register');
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
        $this->em->persist($stock);
    }

    private function calculateDecrease(Stock $stock) {
        $type = $stock->getStockType();

        $currentPrice = $stock->getCurrentPrice();
        $startingPrice = $stock->getStartingPrice();
        $startingStock = $stock->getStartingStock();
        $currentStock = $stock->getCurrentStock();
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
        $notOrdered = $this->em->getRepository('LiberBeleggersBundle:Stock')->inverseFindToBeUpdatedByIdsAndUser($stockIds, $this->user);

        /** @var Stock $stock */
        foreach($notOrdered as $stock) {
            // Refresh because ordered products just got commited and we don't want to decrease these again...
            $currentPrice = $stock->getCurrentPrice();
            $minPrice = $stock->getMinPrice();

            $this->updateHistory($stock, null, new \DateTime(), self::DECREASE);

            $voodoo = $this->calculateDecrease($stock);

            $newPrice = $currentPrice - $voodoo;

            if($newPrice < $minPrice) {
                $newPrice = $minPrice;
            }

            $stock->setCurrentPrice($newPrice);
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

    private function updateHistory(Stock $stock, $amount, \DateTime $now, $type = self::INCREASE) {
        if($type === self::INCREASE) {
            $voodoo = $this->calculateIncrease($stock);
        } else if ($type === self::DECREASE) {
            $voodoo = $this->calculateDecrease($stock);
        }

        $history = new OrderHistory();

        $history->setStock($stock);
        $history->setUser($this->user);
        $history->setPrice($stock->getCurrentPrice());
        $history->setAmount($amount);
        $history->setTime($now);
        $history->setIncrease($voodoo);

        $this->em->persist($history);
    }
}