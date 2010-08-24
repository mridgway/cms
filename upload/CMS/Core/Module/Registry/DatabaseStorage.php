<?php

namespace Core\Module\Registry;

/**
 * Loads modules and components from the database.
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Module
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class DatabaseStorage extends AbstractStorage
{
    protected $_em = null;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->_em = $em;
    }

    public function load()
    {
        return \Zend_Registry::get('doctrine')->getRepository('Core\Model\Module')->findAll();
    }
}