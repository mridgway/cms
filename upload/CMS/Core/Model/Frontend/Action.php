<?php

namespace Core\Model\Frontend;

/**
 * Represents an action that can be performed on the front end
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Action extends \stdClass
{
    public $name;
    public $plugin;
    public $postback;

    public function __construct($name, $postback = null)
    {
        $this->name = $name;
        $this->postback = $postback;
        $this->plugin = null;
    }
}
