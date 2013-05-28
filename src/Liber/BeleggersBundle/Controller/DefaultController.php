<?php

namespace Liber\BeleggersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('LiberBeleggersBundle:Default:index.html.twig', array('name' => $name));
    }
}
