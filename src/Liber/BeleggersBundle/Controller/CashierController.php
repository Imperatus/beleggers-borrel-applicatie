<?php
namespace Liber\BeleggersBundle\Controller;

use Doctrine\ORM\EntityManager;

use Liber\BeleggersBundle\Entity\OrderHistory;
use Liber\BeleggersBundle\Entity\Stock;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\DateTime;

class CashierController extends Controller {
    /** @var  EntityManager */
    private $em;

    protected $container;

    private $user;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->em = $this->container->get('doctrine')->getManager();
        $this->user = $this->get('security.context')->getToken()->getUser();
    }

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

            foreach($stocks as $stockId => $amount) {
                $stock = $this->em->getRepository('LiberBeleggersBundle:Stock')->findOneById($stockId);

                if($this->updateStockAmount($stock, $amount)) {
                    $this->updateHistory($stock, $amount, $now);
                    $this->updateStockPrice($stock);
                } else {
                    //SCREAM!!!
                }
            }
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

    private function updateStockPrice(Stock $stock) {
        $currentPrice = $stock->getCurrentPrice();

        $voodoo = $this->calculateIncrease($stock);

        $newPrice = $currentPrice + $voodoo;

        $stock->setCurrentPrice($newPrice);
        $this->em->persist($stock);
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

    private function updateHistory(Stock $stock, $amount, \DateTime $now) {
        $voodoo = $this->calculateIncrease($stock);

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