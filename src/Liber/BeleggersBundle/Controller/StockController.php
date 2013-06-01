<?php

namespace Liber\BeleggersBundle\Controller;

use Doctrine\ORM\EntityManager;
use Liber\BeleggersBundle\Form\Type\StockCollectionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Liber\BeleggersBundle\Form\Type\StockType;
use Liber\BeleggersBundle\Entity\Stock;
use Symfony\Component\DependencyInjection\ContainerInterface;

class StockController extends Controller
{
    /** @var  EntityManager */
    private $em;

    public function setContainer(ContainerInterface $container = null)
    {

        $this->container = $container;
        $this->em = $this->container->get('doctrine')->getManager();
    }

    public function overviewAction()
    {
        $stock = $this->getDoctrine()->getRepository('LiberBeleggersBundle:Stock')->findAll();
        $activeTab = 'tabOverview';

        return $this->render('LiberBeleggersBundle:Stock:overview.html.twig', array(
            'stock' => $stock,
            'activeTab' => $activeTab,
            'help' => array(
                'pageName' => $this->get('translator')->trans('help.headers.overview'),
                'helpText' => $this->get('translator')->trans('help.texts.overview'),
            ),
        ));
    }

    public function editAction() {
        $stocks = $this->getDoctrine()->getRepository('LiberBeleggersBundle:Stock')->findAll();
        $stock = new Stock();

        $stockCollection = new StockCollectionType();

        $activeTab = 'tabEdit';

        $form = $this->createForm(new StockCollectionType(), $stockCollection);

        $request = $this->getRequest();

        if($request->getMethod() === 'POST') {
            $form->submit($request);
            var_dump($stockCollection);die;

            if($form->isValid()) {
                $this->em->persist($stock);
                $this->em->flush();

            } else {
                var_dump($form->getData());die;
            }

        }

        return $this->render('LiberBeleggersBundle:Stock:edit.html.twig', array(
            'form' => $form->createView(),
            'stock' => $stocks,
            'activeTab' => $activeTab,
            'help' => array(
                'pageName' => $this->get('translator')->trans('help.headers.edit'),
                'helpText' => $this->get('translator')->trans('help.texts.edit'),
            ),
        ));
    }
}
