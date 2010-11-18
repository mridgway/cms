<?php

namespace Core\Validator;

/**
 * TwitterUsername Validator.
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Validator
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class TwitterUsername extends \Zend_Validate_Abstract
{
    const INVALID_CHARS = 'invalidChars';
    const TOO_LONG = 'tooLong';

    /**
     * Error messages
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID_CHARS => "'%value%' must not contain any characters other than letters and numbers",
        self::TOO_LONG => "Username must be no more than 15 characters"
    );

    /**
     * Is $value a valid twitter username
     *
     * @param  string $value
     * @param  array $context
     * @return boolean
     */
    public function isValid($value, array $context = array())
    {
        $valueString = ltrim((string)$value, '@');
        $this->_setValue($valueString);

        $alnumValidator = new \Zend_Validate_Alnum();
        if (!$alnumValidator->isValid($valueString)) {
            $this->_error(self::INVALID_CHARS);
            return false;
        }

        $lengthValidator = new \Zend_Validate_StringLength(array('max' => 15));
        if (!$lengthValidator->isValid($valueString)) {
            $this->_error(self::TOO_LONG);
            return false;
        }

        return true;
    }
}