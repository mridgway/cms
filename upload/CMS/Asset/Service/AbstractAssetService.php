<?php

namespace Asset\Service;

/**
 * Service for assets
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
abstract class AbstractAssetService
{
    protected $_em;

    public function findBySysname($class, $sysname)
    {
        if(\is_string($sysname)) {
            $object = $this->getEntityManager()->getRepository($class)->findOneBySysname($sysname);
        } else {
            throw new Exception('$sysname argument must be a string.');
        }

        if(null == $object) {
            throw new Exception('Could not find ' . $class . ' with sysname = ' . $sysname);
        }

        return $object;
    }

    /**
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function setEntityManager(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }
}