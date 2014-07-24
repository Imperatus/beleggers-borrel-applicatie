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
use Symfony\Component\HttpFoundation\Request;

class LocaleController extends Controller {
    /**
     * Some general constants defined for use in underlying classes, just to keep it clean, y'all
     */
    const REQUEST_METHOD_POST    = 'POST';
    const REQUEST_METHOD_GET     = 'GET';
    const REQUEST_METHOD_PUT     = 'PUT';
    const REQUEST_METHOD_DELETE  = 'DELETE';

    /** @var  EntityManager */
    protected $em;

    /** @var  ContainerInterface */
    protected $container;

    /** @var  User */
    protected $user;

    /**
     * Constructor-like behaviour for this and underlying controllers
     *
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->em        = $this->container->get('doctrine')->getManager();
        $this->user      = $this->get('security.context')->getToken()->getUser();
        $request         = $this->getRequest();

        $this->setLocale($request);
    }

    /**
     * Route to submit the language selection to
     *
     * @param string $locale en | nl
     *
     * @return RedirectResponse
     */
    public function setLanguageAction($locale) {
        $request = $this->getRequest();
        $referer = $request->headers->get('referer'); // Page where user came from...

        // We've got a user? Persist new preference
        if(!empty($this->user)) {
            $this->user->setLocale($locale);
            $this->em->persist($this->user);
            $this->em->flush();
        }

        $request->setLocale($locale);

        // No previous page? Go to homepage
        if(empty($referer)) {
            $referer = $this->generateUrl('SpiritStock_user_homepage');
        }

        //  Doing a hard redirect to re-render template in correct langauge
        return new RedirectResponse($referer);
    }

    /**
     * Sets locale (and translations) according to user preferences
     *
     * @param Request $request
     */
    private function setLocale(Request $request) {
        // Get locale from user profile
        $locale = $this->user->getLocale();

        // Profile information set? Set it in the Request
        if(!empty($locale)) {
            $request->setLocale($locale);
        // Not set? Set it in Request but also persist to profile!
        } else {
            $locale = 'en';
            $request->setLocale('en');
            $this->user->setLocale($locale);

            $this->em->persist($this->user);
            $this->em->flush();
        }

        $this->container->get('twig')->addGlobal('testLocale', $locale);
    }
}