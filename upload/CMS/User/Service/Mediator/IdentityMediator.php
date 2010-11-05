<?php

namespace User\Service\Mediator;

/**
 * @package     CMS
 * @subpackage  User
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class IdentityMediator extends \Core\Service\AbstractMediator
{

    public function init()
    {
        $self = $this;
        $this->setFields(
            array(
                'id' => array(
                    'setMethod' => false
                ),
                'identity' => array(),
                'password' => array()
            )
        );
    }
}