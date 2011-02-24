<?php

namespace User\Model\User;

/**
 * Form block for logging in to the CMS
 *
 * @package     CMS
 * @subpackage  User
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity
 * @Table(name="user_failed_login")
 */
class FailedLogin extends \Core\Model\AbstractModel
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="User\Model\User", inversedBy="failedLogins")
     * @JoinColumn(referencedColumnName="id", nullable="false")
     */
    protected $user;

    /**
     * @Column(type="datetime", nullable="false")
     */
    protected $date;

    /**
     * @Column(type="integer", nullable="false")
     */
    protected $ipAddress;

    /**
     * @Column(type="string", length="255", nullable="false")
     */
    protected $userAgent;

    public function __construct(\User\Model\User $user)
    {
        $this->setUser($user);
        $this->setDate(new \DateTime);
    }
}