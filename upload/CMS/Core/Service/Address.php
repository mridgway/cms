<?php

namespace Core\Service;

/**
 * Service for block functionality
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Address extends \Core\Service\AbstractModel
{
    public function create($data)
    {
        $address = $this->_create($data);
        $this->getEntityManager()->persist($address);
        $this->getEntityManager()->flush();

        return $address;
    }
}