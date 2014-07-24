<?php
namespace SpiritStock\StockBundle\Controller;

use SpiritStock\StockBundle\Entity\OrderHistory;
use SpiritStock\StockBundle\Entity\Stock;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Constraints\Collection;

class CashierController extends LocaleController
{
    const INCREASE = 'increase';
    const DECREASE = 'decrease';

    /**
     * Shows cashier screen
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function orderAction() {
        $types    = $this->em->getRepository('SpiritStockStockBundle:StockType')->findByUser($this->user);
        $stock    = $this->em->getRepository('SpiritStockStockBundle:Stock')->findByUser($this->user);
        $settings = $this->em->getRepository('SpiritStockStockBundle:GlobalSettings')->findOneByUser($this->user);

        return $this->render('SpiritStockStockBundle:Cashier:order.html.twig', array(
            'settings'  => $settings,
            'types'     => $types,
            'stock'     => $stock,
        ));
    }

    /**
     * Route to call the logic that handles the order
     *
     * @throws HttpException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function handleOrderAction() {
        $request = $this->getRequest();

        if ($request->getMethod() === LocaleController::REQUEST_METHOD_POST) {
            $stocks   = $request->request->all();
            $stockIds = array();

            // Loop through stocks and update all relevant values - TODO use of anonymous functions might be more effective/maintainable? Place in own function?
            foreach ($stocks as $stockId => $amount) {
                $stock = $this->em->getRepository('SpiritStockStockBundle:Stock')->findOneById($stockId);

                // Bookkeeping which prices increased, we'll need this to calculate the ones that decrease more efficiently
                array_push($stockIds, $stockId);

                // Make sure this all happens, else throw Exception as things are horribly wrong
                try {
                    $this->updateStockAmount($stock, $amount);
                    $this->updateIncreasedStockPrice($stock);
                    $this->updateDecreasedStockPrice($stockIds);
                } catch (\Exception $e) {
                    throw new HttpException(500, 'Error updating stock, please contact system administrator');
                }
            }

            // Update history for all stock items, flush all changes and set feedback
            $this->updateHistory();
            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('form.order.success'));
            $this->em->flush();
        }

        // Redirect to Cashier page to re-render with new prices
        $url = $this->generateUrl('spiritstock_stock_cashier_register');

        return $this->redirect($url);
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
     * Updates the increased stock price. DOES NOT FLUSH! ONLY FLUSHES IF ALL OTHER FUNCTION CALLS SUCCEED!!!
     *
     * @param Stock $stock The Stock entity
     */
    private function updateIncreasedStockPrice(Stock $stock) {
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
     * Calculates the amount a stock item's price should decrease with the help of black magic
     * As you can see, this is a totally different calculation from the increase, so warrants its own function
     *
     * @param Stock $stock The Stock entity
     *
     * @return float|int
     */
    private function calculateDecrease(Stock $stock) {
        $type = $stock->getStockType();

        /* Not used for current alg. */
        $currentPrice    = $stock->getCurrentPrice();
        $startingStock   = $stock->getStartingStock();
        $currentStock    = $stock->getCurrentStock();

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

    /**
     * Update all stock items that do not qualify for increase and are not frozen
     *
     * @param $stockIds
     */
    private function updateDecreasedStockPrice($stockIds) {
        /** @var Stock[] $notOrdered */
        $notOrdered = $this->em->getRepository('SpiritStockStockBundle:Stock')->inverseFindToBeUpdatedByIdsAndUser($stockIds, $this->user);

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
    private function updateStockAmount(Stock $stock, $amount) {
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
     * Update the order history for graphs
     */
    private function updateHistory() {
        $stock = $this->em->getRepository('SpiritStockStockBundle:Stock')->findByUser($this->user);
        $now   = new \DateTime();

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