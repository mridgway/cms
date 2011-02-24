<?php

namespace Core\Validator;

/**
 * @category   Modo
 * @package    Validator
 * @copyright  Copyright (c) 2010 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: LinkedinProfile.php 528 2010-03-30 13:43:19Z court $
 */
class LinkedinProfile extends \Zend_Validate_Abstract
{
    const INVALID_URL  = 'invalidUrl';
    const TOO_LONG     = 'tooLong';
    const NOT_LINKEDIN = 'notLinkedin';

    /**
     * Error messages
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID_URL  => "'%value%' is not a valid url",
        self::NOT_LINKEDIN => "'%value%' is not a valid linkedin profile",
        self::TOO_LONG     => "Linkedin profile must be no more than 255 characters"
    );

    /**
     * Domain to validate against
     *
     * @var string
     */
    protected $_domain = 'linkedin.com';

    /**
     * Is $value a valid facebook profile link
     *
     * @param  string $value
     * @param  array $context
     * @return boolean
     */
    public function isValid($value, array $context = array())
    {
        $valueString = (string) $value;
        $this->_setValue($valueString);

        if ((!$uri = $this->_getUri($valueString)) || !$uri->valid()) {
            $this->_error(self::INVALID_URL);
            return false;
        }

        $path   = trim($uri->getPath(), '/');
        $query  = $uri->getQuery();
        $domain = $uri->getHost();
        if (0 === strpos($domain, 'www.')) {
            $domain = substr($domain, 4);
        }

        if ($domain != $this->_domain || ($path == '' && $query == '')) {
            $this->_error(self::NOT_LINKEDIN);
            return false;
        }

        $lengthValidator = new \Zend_Validate_StringLength(array('max' => 255));
        if (!$lengthValidator->isValid($valueString)) {
            $this->_error(self::TOO_LONG);
            return false;
        }

        return true;
    }

    /**
     * Get the uri
     *
     * @param  string $value
     * @return \Zend_Uri_Http
     */
    protected function _getUri($value)
    {
        try {
            $uri = \Zend_Uri::factory($value);
        } catch (\Exception $e) {
            return false;
        }

        return $uri;
    }
}