<?php
/**
 * Modo CMS
 */

namespace Modo\Controller\Action\Helper;

/**
 * Temporary abstract class that when extended gives all the functionality of
 * ZF's traditional action helpers but also supports action helpers that are
 * defined in PHP 5.3 namespaces.
 *
 * @todo Replace this helper whenever Zend Framework is updated to support
 *       namespaced action helpers.
 *
 * @category   Modo
 * @package    Controller
 * @subpackage Action\Helper
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: AbstractHelper.php 140 2010-01-28 23:23:07Z court $
 */
abstract class AbstractHelper extends \Zend_Controller_Action_Helper_Abstract
{
    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName()
    {
        $name = get_class($this);

        if (strpos($name, '\\') !== false) {
            $name = strrchr($name, '\\');
        }

        if (strpos($name, '_') !== false) {
            $name = strrchr($name, '_');
        }

        return ltrim($name, '_\\');
    }
}