<?php

namespace SpiritStock\StockBundle\Controller;

use SpiritStock\StockBundle\DataStructure\StockCollection;
use SpiritStock\StockBundle\Entity\GlobalSettings;
use SpiritStock\StockBundle\Form\Type\GlobalSettingsType;
use SpiritStock\StockBundle\Form\Type\StockCollectionType;
use SpiritStock\StockBundle\Form\Type\StockTypeCollectionType;
use SpiritStock\StockBundle\Entity\Stock;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints\Collection;

class SettingsController extends LocaleController
{
    /**
     *  Renders quick overview page of all the settings and stocks
     */
    public function overviewAction()
    {
        $settings  = $this->getDoctrine()->getRepository('SpiritStockStockBundle:GlobalSettings')->findOneByUser($this->user);
        $stock     = $this->getDoctrine()->getRepository('SpiritStockStockBundle:Stock')->findByUser($this->user);
        $types     = $this->getDoctrine()->getRepository('SpiritStockStockBundle:StockType')->findByUser($this->user);
        $activeTab = 'tabOverview';

        return $this->render('SpiritStockStockBundle:Settings:overview.html.twig', array(
            'settings'  => $settings,
            'stock'     => $stock,
            'types'     => $types,
            'activeTab' => $activeTab,
            'help'      => array(
                'pageName' => $this->get('translator')->trans('help.headers.overview'),
                'helpText' => $this->get('translator')->trans('help.texts.overview'),
            ),
        ));
    }

    /**
     * Renders page to edit or create specific stock items and their attributes
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editStockAction()
    {
        $stocks = $this->getDoctrine()->getRepository('SpiritStockStockBundle:Stock')->findByUser($this->user);
        $types  = $this->getDoctrine()->getRepository('SpiritStockStockBundle:StockType')->findByUser($this->user);

        if (empty($types)) {
            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('form.submit.success'));
        }

        $activeTab = 'tabStock';

        // Mock collection object to get the form working... TODO - Fix this so it does it correctly
        $stockCollection = new StockCollection();
        $stockCollection->setStocks($stocks);

        $form = $this->createForm(new StockCollectionType($types), $stockCollection);

        $this->processForm($form, 'stocks', 'SpiritStockStockBundle:Stock');

        $formView = $form->createView();

        return $this->render('SpiritStockStockBundle:Settings:stock.html.twig', array(
            'form'      => $formView,
            'activeTab' => $activeTab,
            'help'      => array(
                'pageName' => $this->get('translator')->trans('help.headers.edit'),
                'helpText' => $this->get('translator')->trans('help.texts.edit'),
            ),
        ));
    }

    /**
     * Renders page to edit or create specific stock types and their attributes
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editTypesAction()
    {
        $activeTab  = 'tabTypes';
        $stockTypes = $this->getDoctrine()->getRepository('SpiritStockStockBundle:StockType')->findByUser($this->user);

        // Mock collection object to get the form working... TODO - Fix this so it does it correctly
        $stockCollection = new StockCollection();
        $stockCollection->setStockTypes($stockTypes);

        $form = $this->createForm(new StockTypeCollectionType(), $stockCollection);

        $this->processForm($form, 'stockTypes', 'SpiritStockStockBundle:StockType');

        return $this->render('SpiritStockStockBundle:Settings:types.html.twig', array(
            'activeTab' => $activeTab,
            'form'      => $form->createView(),
            'help'      => array(
                'pageName' => $this->get('translator')->trans('help.headers.types'),
                'helpText' => $this->get('translator')->trans('help.texts.types'),
            ),
        ));
    }

    /**
     * Renders page to edit Global settings like units and currency
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editGlobalAction()
    {
        $activeTab = 'tabGlobal';
        /** @var GlobalSettings $settings */
        $settings = $this->em->getRepository('SpiritStockStockBundle:GlobalSettings')->findOneByUser($this->user);
        $currency = false;

        if (!$settings) {
            $settings = new GlobalSettings();
            $settings->setCurrency('&euro;');
        } else {
            $currency = $settings->getCurrency();
        }

        if (!$settings || !$currency) {
            $missingSettings = true;
        } else {
            $missingSettings = false;
        }

        $form    = $this->createForm(new GlobalSettingsType(), $settings);
        $request = $this->getRequest();

        // Handle submitted form
        if ($request->getMethod() === LocaleController::REQUEST_METHOD_POST) {
            $form->submit($request);

            if ($form->isValid()) {
                $settings->setUser($this->user);

                $this->em->persist($settings);
                $this->em->flush();

                $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('form.submit.success'));
            } else {
                $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('form.submit.error'));
            }
        }

        return $this->render('SpiritStockStockBundle:Settings:global.html.twig', array(
            'activeTab'       => $activeTab,
            'form'            => $form->createView(),
            'missingSettings' => $missingSettings,
            'help'            => array(
                'pageName' => $this->get('translator')->trans('help.headers.global'),
                'helpText' => $this->get('translator')->trans('help.texts.global'),
            ),
        ));
    }

    /**
     * Reset everything to the initial state
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function resetAction()
    {
        /** @var Stock $stock */
        $stocks = $this->em->getRepository('SpiritStockStockBundle:Stock')->findByUser($this->user);
        foreach ($stocks as $stock) {
            $stock->setCurrentStock($stock->getStartingStock());
            $stock->setCurrentPrice($stock->getStartingPrice());
            $stock->setChangeType(null);
            $stock->setPriceChange(null);

            $this->em->persist($stock);
        }

        $history = $this->em->getRepository('SpiritStockStockBundle:OrderHistory')->findByUser($this->user);
        foreach ($history as $entry) {
            $this->em->remove($entry);
        }

        $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('form.submit.reset'));
        $this->em->flush();

        $url = $this->generateUrl('spiritstock_stock_settings_global');

        return $this->redirect($url);
    }

    /**
     * @param $form
     * @param $entityName
     * @param $entityNamespace
     */
    private function processForm($form, $entityName, $entityNamespace)
    {
        $request = $this->getRequest();
        if ($request->getMethod() === LocaleController::REQUEST_METHOD_POST) {
            /** @var Form $form */
            $form->submit($request);

            if ($entityName === 'stockTypes') {
                /** @var \SpiritStock\StockBundle\Entity\StockType[] $data */
                $data = $form->get($entityName)->getData();
                foreach ($data as $item) {
                    if ($item->getMagicToMaximum() <= 0 || $item->getMagicToMaximum() > 1) {
                        $form->get($entityName)->addError(new FormError('Invalid Magic Number'));
                        break;
                    }

                    if ($item->getStartToMinimum() <= 0 || $item->getStartToMinimum() > 480) {
                        $form->get($entityName)->addError(new FormError('Invalid Time To Minimum'));
                        break;
                    }
                }
            }

            if ($form->isValid()) {
                $editedItems = $form->get($entityName)->getData();
                $idArray     = array();

                $items = $this->getDoctrine()->getRepository($entityNamespace)->findByUser($this->user);

                // Save all stocks that have been edited
                foreach ($editedItems as $item) {
                    /** @var Stock $item */
                    $item->setUser($this->user);
                    $this->em->persist($item);
                    $idArray[$item->getId()] = $item->getId();
                }

                // Remove all stocks that are missing in the form (not done symfony way because I'm messing with it...
                foreach ($items as $item) {
                    if (!in_array($item->getId(), $idArray)) {
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
