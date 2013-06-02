<?php
namespace Liber\BeleggersBundle\Controller;

use Doctrine\ORM\EntityManager;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Collection;
class CashierController extends Controller {
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
        $types = $this->em->getRepository('LiberBeleggersBundle:StockType')->findByUser($this->user);

        return $this->render('LiberBeleggersBundle:Cashier:order.html.twig', array(
            'types' => $types,
        ));
    }

    private function updateStock() {

    }

    private function updateStockPrice($stock) {

    }

    private function updateStockAmount($stock, $amount) {

    }
}