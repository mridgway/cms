<?php

namespace Core\Validator;

/**
 * FacebookProfile validator.
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Validator
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class FacebookProfile extends \Zend_Validate_Abstract
{
    const INVALID_URL  = 'invalidUrl';
    const TOO_LONG     = 'tooLong';
    const NOT_FACEBOOK = 'notFacebook';

    /**
     * Error messages
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID_URL  => "'%value%' is not a valid url",
        self::NOT_FACEBOOK => "'%value%' is not a valid facebook profile",
        self::TOO_LONG     => "Facebook profile must be no more than 255 characters"
    );

    /**
     * Domain to validate against
     *
     * @var string
     */
    protected $_domain = 'facebook.com';

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
            $this->_error(self::NOT_FACEBOOK);
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