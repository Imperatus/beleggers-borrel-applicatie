<?php

namespace Liber\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('LiberUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
