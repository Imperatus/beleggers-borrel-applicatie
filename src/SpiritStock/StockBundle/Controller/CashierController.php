<?php
namespace SpiritStock\StockBundle\Controller;

use SpiritStock\StockBundle\Entity\Stock;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Constraints\Collection;

class CashierController extends LocaleController
{
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
        $request        = $this->getRequest();
        $stockService   = $this->get('stock.service.stock_service');
        $historyService = $this->get('stock.service.history_service');

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
                    $stockService->updateStockAmount($stock, $amount);
                    $stockService->updateIncreasedStockPrice($stock);
                    $stockService->updateDecreasedStockPrice($stockIds, $this->user);
                } catch (\Exception $e) {
                    throw new HttpException(500, 'Error updating stock, please contact system administrator');
                }
            }

            // Update history for all stock items, flush all changes and set feedback
            $historyService->updateHistory($this->user);
            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('form.order.success'));
            $this->em->flush();
        }

        // Redirect to Cashier page to re-render with new prices
        $url = $this->generateUrl('spiritstock_stock_cashier_register');

        return $this->redirect($url);
    }
}