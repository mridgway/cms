<?php

namespace Core\Module\Registry;

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