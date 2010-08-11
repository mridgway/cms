<?php
/**
 * Modo CMS
 */

namespace Core\Model\Frontend;

/**
 * A convenience class for creating frontend objects that only contain an error/success code
 *
 * @category   Model
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Simple.php 297 2010-05-12 13:34:56Z mike $
 */
class Simple extends \Core\Model\Frontend
{

    /**
     *
     */
    public function __construct($code = 0, $message = 'Success')
    {
        parent::__construct();
        $this->setCode($code, $message);
    }

    public function success($msg = 'Success')
    {
        $this->setCode(0, $msg);
        return $this;
    }

    public function fail($msg = 'Fail')
    {
        $this->setCode(1, $msg);
        return $this;
    }
}