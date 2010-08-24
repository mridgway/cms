<?php

namespace Core\Model\Frontend;

/**
 * Contains error/success information and messages
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class Code extends \stdClass
{
    /**
     * 0 = success
     * 1 and above = fail with message
     * -1 and below = success with message
     *
     * @var int
     */
    public $id = 0;

    /**
     * The message
     *
     * @var string
     */
    public $message;

    public function __construct($id, $message = '')
    {
        $this->id = $id;
        $this->message = $message;
    }
}