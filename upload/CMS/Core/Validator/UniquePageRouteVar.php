<?php

namespace Core\Validator;

/**
 * Checks database to make sure the given route does not conflict with an existing route.
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Validator
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class UniquePageRouteVar extends \Zend_Validate_Abstract
{
    CONST PATH = 'path';
    CONST UNIQUE = 'unique';

    private $_em;

    protected $_varName;

    protected $_routeSysname;

    protected $_messageTemplates = array(
        self::PATH => "may only contain letters, numbers, dash(-), underscore(_), or forward slash(/)",
        self::UNIQUE => "there is another page with the same url.  a unique url is required."
    );

    public function __construct($varName, $routeSysname)
    {
        $this->_em = \Zend_Registry::get('doctrine');
        $this->_varName = $varName;
        $this->_routeSysname = $routeSysname;
    }

    public function isValid($value, $context = null)
    {
        $isValid = true;

        if(!$this->isCorrectFormat($value)) {
            $isValid = false;
        }

        if(!$this->isUnique($value, $context)) {
            $isValid = false;
        }

        return $isValid;
    }

    private function isCorrectFormat($value)
    {
        $isValid = true;

        if(strlen(\preg_replace('/[\w-]/', '', $value)) != 0) {
            $isValid = false;
            $this->_error(self::PATH);
        }

        return $isValid;
    }

    private function isUnique($value, $context)
    {
        $isValid = true;

        if (\array_key_exists('currentSysname', $context)) {
            if ($value == $context['currentSysname']) {
                return $isValid;
            }
        }

        $route = $this->_em->getRepository('Core\Model\Route')->findBySysname($this->_routeSysname);
        $pageRoutesWithVar = $this->_em->getRepository('Core\Model\PageRoute')->getPageIdForRoute($route->getId(), array($this->_varName => $value));
        if (count($pageRoutesWithVar) > 0) {
            $isValid = false;
            $this->_error(self::UNIQUE);
        }

        return $isValid;
    }
}