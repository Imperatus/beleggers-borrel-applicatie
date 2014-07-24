<?php
namespace SpiritStock\StockBundle\Controller;

use Symfony\Component\Validator\Constraints\Collection;

use SpiritStock\StockBundle\Entity\GlobalSettings;

class DashboardController extends LocaleController
{
    /**
     * Just renders the Dashboard
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homeAction() {
        /** @var GlobalSettings $settings */
        $settings = $this->em->getRepository('SpiritStockStockBundle:GlobalSettings')->findOneByUser($this->user);

        // Get the preferred currency if there are settings
        if(!empty($settings)) {
            $currency = $settings->getCurrency();
        }

        // Check if all required settings are there. If not, notify front-end (not elegant, but time pressure)
        if (empty($settings) || empty($currency)) {
            $missingSettings = true;
        } else {
            $missingSettings = false;
        }

        return $this->render('SpiritStockStockBundle:Dashboard:welcome.html.twig', array(
            'user'            => $this->user,
            'missingSettings' => $missingSettings,
        ));
    }
}