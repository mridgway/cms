<?php

namespace Core\Validator;

/**
 * Url validator.
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Validator
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Url extends \Zend_Validate_Abstract
{
    const INVALID_URL = 'invalidUrl';

    /**
     * Error messages
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID_URL   => "'%value%' is not a valid URL."
    );


    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if $value matches the compare field
     *
     * @param  string $value
     * @param  array  $context
     * @return boolean
     */
    public function isValid($value, array $context = array())
    {
        $valueString = (string) $value;
        $this->_setValue($valueString);

        if (!\Zend_Uri::check($value)) {
            $this->_error(self::INVALID_URL);
            return false;
        }
        return true;
    }
}
