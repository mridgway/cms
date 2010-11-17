<?php

namespace User\Model\Identity;

/**
 * Representation of a login identity
 *
 * @package     CMS
 * @subpackage  User
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity
 */
class OpenID extends \User\Model\Identity
{
    /**
     * @var string
     * @Column(type="string", length="100", nullable="false")
     */
    protected $provider;
}