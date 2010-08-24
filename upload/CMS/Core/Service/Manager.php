<?php

namespace Core\Service;

/**
 * A singleton class to load services without duplicating loaded services. Also
 * provides a convenient interface for loading and using services on one line.
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class Manager extends \Zend_Registry
{
    protected static $_em;
    
    public static function get($class)
    {
        $instance = self::getInstance();
        if (!$instance->offsetExists($class)) {
            $instance->offsetSet($class, new $class(self::$_em));
        }
        return $instance->offsetGet($class);
    }

    public static function setEntityManager($em)
    {
        self::$_em = $em;
    }
}