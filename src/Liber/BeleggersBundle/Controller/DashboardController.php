<?php
namespace Liber\BeleggersBundle\Controller;

use Doctrine\ORM\EntityManager;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\DateTime;
use Liber\BeleggersBundle\Entity\GlobalSettings;

class DashboardController extends Controller {
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