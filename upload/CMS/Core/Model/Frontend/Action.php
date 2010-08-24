<?php

namespace Core\Model\Frontend;

/**
 * Represents an action that can be performed on the front end
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class Action extends \stdClass
{
    public $name;
    public $source;
    public $plugin;
    public $postback;

    public function __construct($name, $postback = null)
    {
        $this->name = $name;
        $this->postback = $postback;
        $this->source = null;
        $this->plugin = null;
    }
}
