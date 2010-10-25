<?php

namespace Core\Service\Mediator;

/**
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class AbstractMediator extends \ZendX\Doctrine2\FormMediator
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var Core\Auth\Auth
     */
    protected $auth;

    /**
     * @param Doctrine\ORM\EntityManager $em
     */
    public function setEntityManager(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * @param \Core\Auth\Auth $auth
     */
    public function setAuth(\Core\Auth\Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @return Core\Auth\Auth
     */
    public function getAuth()
    {
        return $this->auth;
    }
}