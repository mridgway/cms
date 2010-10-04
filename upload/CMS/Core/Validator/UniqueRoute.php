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
class UniqueRoute extends \Zend_Validate_Abstract
{
    const NOT_UNIQUE = 'notUnique';

    /**
     * A reference to the Route that causes the conflict. null if no conflict or haven't validated
     * yet
     *
     * @var Core\Model\Route
     */
    protected $_conflict = null;
    
    /**
     * Error messages
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_UNIQUE => "This route conflicts with an existing route"
    );

    /**
     * Ignore the id that is in the field with this name
     * @var string
     */
    protected $_ignoreFieldName = null;

    /**
     * Constructor
     *
     * Set the field to ignore
     *
     * @param string $ignoreField
     */
    public function __construct($ignoreField = null)
    {
        if ($ignoreField) {
            $this->setIgnoreField($ignoreField);
        }
    }

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if $value is unique from existing alias
     *
     * @param  string $value
     * @param  array  $context
     * @return boolean
     */
    public function isValid($value, array $context = null)
    {
        $valueString = (string)$value;
        $this->_setValue($valueString);

        $routeService = new \Core\Service\Route(\Zend_Registry::get('doctrine'));
        if ($this->_duplicate = $routeService->getDuplicateFor($this->_value)) {
            if ($this->getIgnoreField()
                && isset($context[$this->getIgnoreField()])
                && $context[$this->getIgnoreField()] == $this->_duplicate->id) {
                return true;
            }
            $this->_conflict = $this->_duplicate->template;
            $this->_error();
            return false;
        }
        return true;
    }

    /**
     * Return the conflicting route
     *
     * @return Core\Model\Route
     */
    public function getConflict()
    {
        return $this->_conflict;
    }

    /**
     * Set the ignore field name
     *
     * @param  string $field
     * @return Page_Validate_UniqueSysname *Provides fluid interface*
     */
    public function setIgnoreField($field)
    {
        $this->_ignoreFieldName = (string)$field;

        return $this;
    }

    /**
     * Get the ignore field name
     *
     * @return string|null
     */
    public function getIgnoreField()
    {
        return $this->_ignoreFieldName;
    }
}