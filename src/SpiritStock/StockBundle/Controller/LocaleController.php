<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ijay
 * Date: 6/9/13
 * Time: 9:49 PM
 * To change this template use File | Settings | File Templates.
 */

namespace SpiritStock\StockBundle\Controller;

use Doctrine\ORM\EntityManager;
use SpiritStock\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LocaleController extends Controller {
    /** @var  EntityManager */
    protected $em;

    /** @var  ContainerInterface */
    protected $container;

    /** @var  User */
    protected $user;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->em = $this->container->get('doctrine')->getManager();
        $this->user = $this->get('security.context')->getToken()->getUser();

        $request = $this->getRequest();
        $this->setLocale($request);
    }

    private function setLocale($request) {
        $locale = $this->user->getLocale();
        if(!empty($locale)) {
            $request->setLocale($locale);
        } else {
            $locale = 'en';
            $request->setLocale('en');
            $this->user->setLocale($locale);
            $this->em->persist($this->user);
            $this->em->flush();
        }

        $this->container->get('twig')->addGlobal('testLocale', $locale);
    }

    public function setLanguageAction($locale) {
        $request = $this->getRequest();
        $referer = $request->headers->get('referer');

        if(!empty($this->user)) {
            $this->user->setLocale($locale);
            $this->em->persist($this->user);
            $this->em->flush();
        }

        $request->setLocale($locale);

        if(empty($referer)) {
            $referer = $this->generateUrl('SpiritStock_user_homepage');
        }

        return new RedirectResponse($referer);
    }
}