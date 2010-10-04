<?php

namespace Core\Model\Frontend;

/**
 * A convenience class for creating frontend objects that only contain an error/success code
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
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