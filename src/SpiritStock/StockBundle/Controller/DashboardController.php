<?php
namespace SpiritStock\StockBundle\Controller;

use Doctrine\ORM\EntityManager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Collection;
use SpiritStock\StockBundle\Controller\LocaleController;
use SpiritStock\StockBundle\Entity\GlobalSettings;

class DashboardController extends LocaleController {

    public function homeAction() {
        /** @var GlobalSettings $settings */
        $settings = $this->em->getRepository('SpiritStockStockBundle:GlobalSettings')->findOneByUser($this->user);

        if(!empty($settings)) {
            $currency = $settings->getCurrency();
        }

        if(empty($settings) || empty($currency)) {
            $missingSettings = true;
        } else {
            $missingSettings = false;
        }

        return $this->render('SpiritStockStockBundle:Dashboard:welcome.html.twig', array(
            'user' => $this->user,
            'missingSettings' => $missingSettings,
        ));
    }

}