<?php

namespace Core\Validator;

class Route extends \Zend_Validate_Abstract
{
    CONST PATH = 'path';
    CONST UNIQUE = 'unique';

    private $_em;
    private $_context;

    protected $_messageTemplates = array(
        self::PATH => "may only contain letters, numbers, dash(-), underscore(_), or forward slash(/)",
        self::UNIQUE => "there is another page with the same url.  a unique url is required."
    );

    public function __construct()
    {
        $this->_em = \Zend_Registry::get('doctrine');
    }

    public function isValid($value, $context = null)
    {
        $this->_context = $context;

        $isValid = true;

        if(!$this->correctFormat($value))
        {
            $isValid = false;
        }

        if(!$this->isUnique($value, $context))
        {
            $isValid = false;
        }

        return $isValid;
    }

    private function correctFormat($value)
    {
        $isValid = true;

        if(strlen(\preg_replace('/[\w-\/]/', '', $value)) != 0)
        {
            $isValid = false;
            $this->_error(self::PATH);
        }

        return $isValid;
    }

    private function isUnique($value, $context)
    {
        $isValid = true;

        if(!\array_key_exists('currentRoute', $context))
        {
            throw new \Exception('&#60input type="hidden" name="currentRoute"&#62 is required in the form for unique route validation.');
        }

        if ($value == $context['currentRoute']) {
            return $isValid;
        }

        $route = $this->_em->getRepository('Core\Model\Route')->findByTemplate($value);
        if($route > 0)
        {
            $isValid = false;
            $this->_error(self::UNIQUE);
        }

        return $isValid;
    }
}