<?php
namespace Liber\BeleggersBundle\Controller;

use Doctrine\ORM\EntityManager;

use Liber\BeleggersBundle\Entity\Stock;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Collection;
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
        $request = $this->getRequest();
        if($request->getMethod() === 'POST') {
            $stocks = $request->request->all();

            foreach($stocks as $stockId => $amount) {
                $stock = $this->em->getRepository('LiberBeleggersBundle:Stock')->findOneById($stockId);

                if($this->updateStockAmount($stock, $amount)) {

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

    private function updateStockPrice($stock) {

    }

    /**
     * @param Stock $stock
     * @param $amount
     * @return bool
     */
    private function updateStockAmount(Stock $stock, $amount) {
        $current = $stock->getCurrentStock();
        $new = $current - $amount;
        $stock->setCurrentStock($new);

        $this->em->persist($stock);
        return true;
    }
}