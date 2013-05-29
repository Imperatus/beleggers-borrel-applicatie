<?php

namespace Liber\BeleggersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StockController extends Controller
{
    public function overviewAction()
    {
        $stock = $this->getDoctrine()->getRepository('LiberBeleggersBundle:Stock')->findAll();
        $activeTab = 'tabOverview';

        return $this->render('LiberBeleggersBundle:Stock:overview.html.twig', array(
            'stock' => $stock,
            'activeTab' => $activeTab,
        ));
    }
}
