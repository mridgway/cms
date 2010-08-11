<?php
/**
 * Modo CMS
 */

namespace Core\Service;

/**
 * A singleton class to load services without instantiating 30 bajillion objects. Also makes
 * using a service easier so that you can load a service and use it in one line.
 *
 * @category   Manager
 * @package    Modo
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Manager.php 297 2010-05-12 13:34:56Z mike $
 */
class Manager extends \Zend_Registry
{
    protected static $_em;
    
    public static function get($module, $name = '')
    {
        $instance = self::getInstance();
        if ($name == '') {
            $name = $module;
            $module = 'Core';
        }
        $key = $module . '\\Service\\' . $name;
        if (!$instance->offsetExists($key)) {
            $instance->offsetSet($key, new $key(self::$_em));
        }
        return $instance->offsetGet($key);
    }

    public static function setEntityManager($em)
    {
        self::$_em = $em;
    }
}