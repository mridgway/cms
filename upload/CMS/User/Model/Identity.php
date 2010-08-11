<?php
/**
 * Modo CMS
 */

namespace User\Model;

/**
 * Description of Identity
 *
 * @category   Model
 * @package    User
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Identity.php 297 2010-05-12 13:34:56Z mike $
 *
 * @Entity
 * @Table(name="Identity")
 *
 * @property int $id
 * @property string $value
 * @property string $type
 * @property \User\Model\User $user
 */
class Identity extends \Modo\Orm\Model\AbstractModel
{
    /**
     * @var integer
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @Column(type="string", name="identity", length="500", unique="true", nullable="false")
     */
    protected $identity;

    /**
     * @var string
     * @Column(type="string", name="passHash", length="128", nullable="true")
     */
    protected $passHash;

    /**
     * @TODO make this a foreign key?
     * @var string
     * @Column(type="string", name="type", length="100", nullable="false")
     */
    protected $type;

    /**
     * @var \User\Model\User
     * @ManyToOne(targetEntity="User\Model\User")
     * @JoinColumn(name="user_id", referencedColumnName="id", nullable="false")
     */
    protected $user;

    /**
     * @param string $type
     * @param string $identity
     */
    public function __construct($type, $identity, \User\Model\User $user)
    {
        $this->setType($type);
        $this->setIdentity($identity);
        $this->setUser($user);
    }

    /**
     * @param string $identity
     * @return Identity
     */
    public function setIdentity($identity)
    {
        $validator = new \Zend_Validate_StringLength(1, 500);
        if (!$validator->isValid($identity)) {
            throw new \Modo\Model\Exception('Identity must be between 1 and 500 charactrs');
        }
        $this->identity = $identity;
        return $this;
    }

    /**
     *
     * @param string $passHash
     * @return Identity
     */
    public function setPassHash($passHash = null)
    {
        if (null !== $passHash) {
            $validator = new \Zend_Validate_StringLength(1, 128);
            if (!$validator->isValid($passHash)) {
                throw new \Modo\Model\Exception('Password must be between 1 and 128 charactrs');
            }
        }
        $this->passHash = $passHash;
        return $this;
    }

    /**
     * @param string $type
     * @return Identity
     */
    public function setType($type)
    {
        $validator = new \Zend_Validate_StringLength(1, 100);
        if (!$validator->isValid($type)) {
            throw new \Modo\Model\Exception('Type must be between 1 and 100 charactrs');
        }
        $this->type = $type;
        return $this;
    }

    /**
     * @param User $user
     * @return Identity
     */
    public function setUser(\User\Model\User $user)
    {
        $this->user = $user;
        return $this;
    }
}