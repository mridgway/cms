<?php
/**
 * Modo CMS
 */

namespace User\Model;

/**
 * Description of Session
 *
 * @category   Model
 * @package    User
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Session.php 297 2010-05-12 13:34:56Z mike $
 *
 * @Entity
 * @Table(name="Session")
 *
 * @property int $id
 * @property \User\Model\User $user
 * @property \DateTime $dateStarted
 * @property \DateTime $dateActive
 * @property string $ipAddress
 * @property string $userAgent
 */
class Session extends \Modo\Orm\Model\AbstractModel
{
    /**
     * @var integer
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \User\Model\User
     * @ManyToOne(targetEntity="User\Model\User")
     * @JoinColumn(name="user_id", referencedColumnName="id", nullable="false")
     */
    protected $user;

    /**
     * @var \DateTime
     * @Column(type="datetime", name="date_started", nullable="false")
     */
    protected $dateStarted;

    /**
     * @var \DateTime
     * @Column(type="datetime", name="date_active", nullable="false")
     */
    protected $dateActive;

    /**
     * @var string
     * @Column(type="string", name="ip_address", length="128", nullable="true")
     */
    protected $ipAddress;

    /**
     * @var string
     * @Column(type="string", name="user_agent", length="255", nullable="true")
     */
    protected $userAgent;

    /**
     * @param \User\Model\User $user
     * @param string $ipAddress
     * @param string $userAgent
     */
    public function __construct(\User\Model\User $user, $ipAddress = null, $userAgent = null)
    {
        $this->setUser($user);
        $this->SetDateStarted(new \DateTime());
        $this->setDateActive(new \DateTime());
        $this->setIpAddress($ipAddress);
        $this->setUserAgent($userAgent);
    }

    /**
     * @param User $user
     * @return Session
     */
    public function setUser(\User\Model\User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param DateTime $date
     * @return Session
     */
    public function setDateStarted(\DateTime $date = null)
    {
        if (null === $date){
            $date = new \DateTime();
        }
        $this->dateStarted = $date;
        return $this;
    }

    /**
     * @param DateTime $date
     * @return Session
     */
    public function setDateActive(\DateTime $date = null)
    {
        if (null === $date){
            $date = new \DateTime();
        }
        $this->dateActive = $date;
        return $this;
    }

    /**
     * @param string $ipAddress
     * @return Session
     */
    public function setIpAddress($ipAddress = null)
    {
        if (null !== $ipAddress) {
            $validator = new \Zend_Validate_StringLength(0, 128);
            if (!$validator->isValid($ipAddress)) {
                throw new \Modo\Model\Exception('Email must be between 0 and 128 charactrs');
            }
        }
        $this->ipAddress = $ipAddress;
        return $this;
    }

    /**
     * @param string $userAgent
     * @return Session
     */
    public function setUserAgent($userAgent = null)
    {
        if (null !== $userAgent) {
            $validator = new \Zend_Validate_StringLength(0, 255);
            if (!$validator->isValid($userAgent)) {
                throw new \Modo\Model\Exception('Email must be between 0 and 255 charactrs');
            }
        }
        $this->userAgent = $userAgent;
        return $this;
    }
}