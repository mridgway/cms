<?php

namespace Core\Model;

/**
 * A wrapper for JSON output
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
abstract class Frontend extends \stdClass
{
    /**
     * @var string
     */
    public $type = '';

    /**
     * @var Code
     */
    public $code;

    /**
     * @var array
     */
    public $data;

    public function __construct()
    {
        $this->setCode();
        $this->data = array();
        $this->templates = array();
        $this->html = '';
    }

    public function __toString()
    {
        $output = \Zend_Json::encode($this, \Zend_Json::TYPE_OBJECT);
        // return \Zend_Json::prettyPrint($output);
        return $output;
    }

    public function setCode($id = 0, $message = 'Success')
    {
        $this->code = new Frontend\Code($id, $message);
        return $this;
    }
}