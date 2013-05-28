<?php

namespace Liber\BeleggersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StockController extends Controller
{
    public function overviewAction()
    {
        return $this->render('LiberBeleggersBundle:Stock:index.html.twig', array());
    }
}
