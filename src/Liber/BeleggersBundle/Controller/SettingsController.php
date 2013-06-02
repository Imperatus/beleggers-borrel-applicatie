<?php

namespace Liber\BeleggersBundle\Controller;

use Doctrine\ORM\EntityManager;

use Liber\BeleggersBundle\DataStructure\StockCollection;
use Liber\BeleggersBundle\Form\Type\StockCollectionType;
use Liber\BeleggersBundle\Form\Type\StockTypeCollectionType;
use Liber\BeleggersBundle\Entity\Stock;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints\Collection;

class SettingsController extends Controller
{
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

    public function overviewAction()
    {
        $stock = $this->getDoctrine()->getRepository('LiberBeleggersBundle:Stock')->findByUser($this->user);
        $types = $this->getDoctrine()->getRepository('LiberBeleggersBundle:StockType')->findByUser($this->user);

        $activeTab = 'tabOverview';

        return $this->render('LiberBeleggersBundle:Settings:overview.html.twig', array(
            'stock' => $stock,
            'types' => $types,
            'activeTab' => $activeTab,
            'help' => array(
                'pageName' => $this->get('translator')->trans('help.headers.overview'),
                'helpText' => $this->get('translator')->trans('help.texts.overview'),
            ),
        ));
    }

    public function editAction() {
        $stocks = $this->getDoctrine()->getRepository('LiberBeleggersBundle:Stock')->findByUser($this->user);

        $activeTab = 'tabEdit';

        // Mock collection object to get the form working... TODO - Fix this so it does it correctly
        $stockCollection = new StockCollection();
        $stockCollection->setStocks($stocks);

        $form = $this->createForm(new StockCollectionType(), $stockCollection);

        $this->processForm($form, 'stocks', 'LiberBeleggersBundle:Stock');

        $formView = $form->createView();

        return $this->render('LiberBeleggersBundle:Settings:edit.html.twig', array(
            'form' => $formView,
            'activeTab' => $activeTab,
            'help' => array(
                'pageName' => $this->get('translator')->trans('help.headers.edit'),
                'helpText' => $this->get('translator')->trans('help.texts.edit'),
            ),
        ));
    }

    public function editTypesAction() {
        $activeTab = 'tabEditTypes';


        $stockTypes = $this->getDoctrine()->getRepository('LiberBeleggersBundle:StockType')->findByUser($this->user);

        // Mock collection object to get the form working... TODO - Fix this so it does it correctly
        $stockCollection = new StockCollection();
        $stockCollection->setStockTypes($stockTypes);

        $form = $this->createForm(new StockTypeCollectionType(), $stockCollection);

        $this->processForm($form, 'stockTypes', 'LiberBeleggersBundle:StockType');

        return $this->render('LiberBeleggersBundle:Settings:types.html.twig', array(
            'activeTab' => $activeTab,
            'form' => $form->createView(),
            'help' => array(
                'pageName' => $this->get('translator')->trans('help.headers.types'),
                'helpText' => $this->get('translator')->trans('help.texts.types'),
            ),
        ));
    }

    private function processForm($form, $entityName, $entityNamespace) {
        $request = $this->getRequest();
        if($request->getMethod() === 'POST') {
            /** @var Form $form */
            $form->submit($request);

            if($entityName === 'stockTypes') {
                $data = $form->get($entityName)->getData();
                foreach($data as $item) {
                    if($item->getMagicToMaximum() <= 0 || $item->getMagicToMaximum() > 1) {
                        $form->get($entityName)->addError(new FormError('Invalid Magic Number'));
                        break;
                    }

                    if($item->getStartToMinimum() <= 0 || $item->getStartToMinimum() > 480) {
                        $form->get($entityName)->addError(new FormError('Invalid Time To Minimum'));
                        break;
                    }
                }
            }

            if($form->isValid()) {
                $editedItems = $form->get($entityName)->getData();
                $idArray = array();

                $items = $this->getDoctrine()->getRepository($entityNamespace)->findByUser($this->user);

                // Save all stocks that have been edited
                foreach($editedItems as $item) {
                    /** @var Stock $item */
                    $item->setUser($this->user);
                    $this->em->persist($item);
                    $idArray[$item->getId()] = $item->getId();
                }

                // Remove all stocks that are missing in the form (not done symfony way because I'm messing with it...
                foreach($items as $item) {
                    if(!in_array($item->getId(), $idArray)) {
                        $this->em->remove($item);
                    }
                }
                // Persist to database
                $this->em->flush();
                $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('form.submit.success'));
            } else {
                $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('form.submit.error'));
            }
        }
    }
}
