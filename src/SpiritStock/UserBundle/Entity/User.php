<?php
// src/SpiritStock/UserBundle/Entity/User.php

namespace SpiritStock\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;


class User extends BaseUser
{

    protected $id;

    public function __construct()
    {
        parent::__construct();
        $this->locale = 'en';
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @var string
     */
    private $locale;


    /**
     * Set locale
     *
     * @param string $locale
     * @return User
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    
        return $this;
    }

    /**
     * Get locale
     *
     * @return string 
     */
    public function getLocale()
    {
        return $this->locale;
    }
}