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
 * @version    $Id: Code.php 297 2010-05-12 13:34:56Z mike $
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