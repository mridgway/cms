<?php

namespace User\Model;

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
 * @Table(name="user_identity")
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="type", type="string")
 * @DiscriminatorMap({
 *      "Local" = "User\Model\Identity\Local",
 *      "OpenID" = "User\Model\Identity\OpenID"
 * })
 *
 * @property int $id
 * @property string $value
 * @property string $type
 * @property \User\Model\User $user
 */
class Identity extends \Core\Model\AbstractModel
{
    /**
     * @var integer
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @Column(type="string", length="500", unique="true", nullable="false")
     */
    protected $identifier;

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
    public function __construct($identity, \User\Model\User $user)
    {
        $this->setIdentifier($identity);
        $this->setUser($user);
    }

    /**
     * @param string $identifier
     * @return Identity
     */
    public function setIdentifier($identifier)
    {
        $validator = new \Zend_Validate_StringLength(1, 500);
        if (!$validator->isValid($identifier)) {
            throw new \Core\Model\Exception('Identifier must be between 1 and 500 charactrs');
        }
        $this->identifier = $identifier;
        return $this;
    }

    /**
     * Hashes a password and sets it as the pass hash
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $hasher = new \Core\Auth\Hasher();
        $this->setPassHash($hasher->hash($password));
        return $this;
    }

    /**
     *
     * @param string $passHash
     * @return Identity
     */
    protected function setPassHash($passHash = null)
    {
        if (null !== $passHash) {
            $validator = new \Zend_Validate_StringLength(32);
            if (!$validator->isValid($passHash)) {
                throw new \Core\Model\Exception('PassHash is not properly hashed');
            }
        }
        $this->passHash = $passHash;
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

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function toArray($includes = array())
    {
        $data = array();
        $data['id'] = $this->getId();
        $data['identifier'] = $this->getIdentifier();

        return $data;
    }

    public function fromArray(array $data)
    {
        $this->_setIfSet('identifier', $data);
    }

    public static function createFromArray(array $data)
    {
        $identity = new self($data['identifier'], $data['user']);
        return $identity;
    }
}