<?php

namespace Liber\BeleggersBundle\Controller;

use Doctrine\ORM\EntityManager;
use Liber\BeleggersBundle\DataStructure\StockCollection;
use Liber\BeleggersBundle\Form\Type\StockCollectionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Liber\BeleggersBundle\Form\Type\StockType;
use Liber\BeleggersBundle\Entity\Stock;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Collection;

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

        $activeTab = 'tabEdit';

        // Mock collection object to get the form working... TODO - Fix this so it does it correctly
        $stockCollection = new StockCollection();
        $stockCollection->setStocks($stocks);

        $form = $this->createForm(new StockCollectionType(), $stockCollection);

        $request = $this->getRequest();
        if($request->getMethod() === 'POST') {
            $form->submit($request);

            if($form->isValid()) {
                $editedStocks = $form->get('stocks')->getData();
                $idArray = array();

                $stocks = $this->getDoctrine()->getRepository('LiberBeleggersBundle:Stock')->findAll();

                // Save all stocks that have been edited
                foreach($editedStocks as $stock) {
                   $this->em->persist($stock);
                   $idArray[$stock->getId()] = $stock->getId();
                }

                // Remove all stocks that are missing in the form (not done symfony way because I'm messing with it...
                foreach($stocks as $stock) {
                    if(!in_array($stock->getId(), $idArray)) {
                        $this->em->remove($stock);
                    }
                }

                $this->em->flush();

            } else {
                var_dump($form->getErrorsAsString());die;
            }

        }
        $formView = $form->createView();

        return $this->render('LiberBeleggersBundle:Stock:edit.html.twig', array(
            'form' => $formView,
            'stock' => $stocks,
            'activeTab' => $activeTab,
            'help' => array(
                'pageName' => $this->get('translator')->trans('help.headers.edit'),
                'helpText' => $this->get('translator')->trans('help.texts.edit'),
            ),
        ));
    }
}
