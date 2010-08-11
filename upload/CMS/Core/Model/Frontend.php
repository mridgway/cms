<?php
/**
 * Modo CMS
 */

namespace Core\Model;

/**
 * A wrapper for JSON output
 *
 * @category   Model
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Frontend.php 297 2010-05-12 13:34:56Z mike $
 */
abstract class Frontend extends \stdClass
{
    /**
     * @var Code
     */
    public $code;

    /**
     * @var array
     */
    public $data;

    /**
     * @var array
     */
    public $templates;

    /**
     * @var string
     */
    public $html;

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