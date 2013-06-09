<?php
namespace Liber\BeleggersBundle\Controller;

use Doctrine\ORM\EntityManager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Liber\BeleggersBundle\Controller\LocaleController;
use Liber\BeleggersBundle\Entity\GlobalSettings;

class DashboardController extends LocaleController {

    public function homeAction() {
        /** @var GlobalSettings $settings */
        $settings = $this->em->getRepository('LiberBeleggersBundle:GlobalSettings')->findOneByUser($this->user);

        if(!empty($settings)) {
            $currency = $settings->getCurrency();
        }

        if(empty($settings) || empty($currency)) {
            $missingSettings = true;
        } else {
            $missingSettings = false;
        }

        return $this->render('LiberBeleggersBundle:Dashboard:welcome.html.twig', array(
            'user' => $this->user,
            'missingSettings' => $missingSettings,
        ));
    }

}