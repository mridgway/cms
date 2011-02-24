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
class Local extends \User\Model\Identity
{

    /**
     * @var string
     * @Column(type="string", name="passHash", length="128", nullable="true")
     */
    protected $passHash;

    public function __construct($identity, $password, \User\Model\User $user)
    {
        parent::__construct($identity, $user);
        $this->setPassword($password);
    }

    /**
     * Hashes a password and sets it as the pass hash
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $validator = new \Zend_Validate_StringLength(7);
        if (!$validator->isValid($password)) {
            throw new \Core\Model\Exception('Password too short');
        }
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

    public function fromArray(array $data)
    {
        parent::fromArray($data);
        $this->_setIfSet('password', $data);
    }

    public static function createFromArray(array $data)
    {
        if (!\array_key_exists('identifier', $data)
                || !\array_key_exists('password', $data)
                || !\array_key_exists('user', $data)) {
            throw new \Core\Exception\ValidationException('Not enough data provided');
        }

        $identity = new Local($data['identifier'], $data['password'], $data['user']);
        return $identity;
    }
}