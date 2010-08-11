<?php
/**
 * Modo CMS
 */

namespace Core\Model\Frontend;

/**
 * Contains error/success information and messages
 *
 * @category   Model
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Action.php 297 2010-05-12 13:34:56Z mike $
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
