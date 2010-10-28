<?php

namespace User\Service;

/**
 * Service for users
 *
 * @package     CMS
 * @subpackage  User
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class User extends \Core\Service\AbstractService
{
    /**
     * @param intger $identifier
     */
    public function getUser($identifier)
    {
       return $this->getEntityManager()->getRepository('User\Model\User')->find($identifier);
    }
}