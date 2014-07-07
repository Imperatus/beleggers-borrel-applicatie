<?php

namespace SpiritStock\AdminBundle\Controller;

use SpiritStock\StockBundle\Controller\LocaleController;

class DefaultController extends LocaleController
{
    public function indexAction()
    {
        return $this->render('SpiritStockAdminBundle:Default:index.html.twig', array(
            'user' => $this->user,
        ));
    }
}
