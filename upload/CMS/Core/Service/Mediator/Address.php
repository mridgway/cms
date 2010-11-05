<?php

namespace Core\Service\Mediator;

/**
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Address extends \Core\Service\AbstractMediator
{
    public function init()
    {
        $self = $this;
        $this->setFields(array(
            'id' => array(
                'setMethod' => false
            ),
            'addressLine1' => array(),
            'addressLine2' => array(),
            'city' => array(),
            'state' => array(),
            'zip' => array()
            )
        );
    }
}