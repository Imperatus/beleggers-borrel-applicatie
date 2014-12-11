<?php

namespace SpiritStock\AdminBundle\Controller;


use SpiritStock\StockBundle\Controller\LocaleController;
use SpiritStock\UserBundle\Entity\User;

/**
 * User controller.
 */
class UserController extends LocaleController
{
    /**
     * Index page for users
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em       = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('SpiritStockUserBundle:User')->findAll();

        return $this->render('SpiritStockAdminBundle:User:index.html.twig', array(
            'entities' => $entities,
            'user'     => $this->user,
        ));
    }

    /**
     * Show user with given ID
     * TODO - Generate CRUD via command line tool
     *
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showAction($id)
    {
        $em     = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SpiritStockUserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        return $this->render('SpiritStockAdminBundle:User:show.html.twig', array(
            'entity' => $entity,
        ));
    }
}
