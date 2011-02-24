<?php

namespace Core\Auth\Adapter;

/**
 * @package     CMS
 * @subpackage  Core
 * @category    Auth
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class GHGIdentity implements \Zend_Auth_Adapter_Interface
{
    const LOGIN_ERROR = 'The login credentials provided were invalid';
    const TOO_MANY_ATTEMPTS = 'You have too many failed login attempts. Please try again in a few minutes.';
    const UNVALIDATED_USER = 'Unvalidated users cannot login';

    protected $_em;

    protected $_maxAttempts = 5;

    protected $identity;

    protected $passHash;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->_em = $em;
    }

    public function setIdentity($email)
    {
        $this->identity = $email;
        return $this;
    }

    public function setPassword($password)
    {
        $hash = new \Core\Filter\PassHash();
        $this->passHash = $hash->filter($password);
        return $this;
    }

    /**
     * Performs an authentication attempt
     *
     * @throws Zend_Auth_Adapter_Exception If authentication cannot be performed
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {

        try {
            $qb = $this->_em->getRepository('User\Model\Identity')->createQueryBuilder('i');
            $qb->innerJoin('i.user', 'u');
            $qb->andWhere('u.email = :email');
            $qb->setParameter('email', $this->identity);
            $identity = $qb->getQuery()->getSingleResult();
            $currentUser = $identity->getUser();

            if ($currentUser->getGroup()->getSysname() == 'guest') {
                throw new \Doctrine\ORM\NoResultException(self::UNVALIDATED_USER);
            }
        } catch (\Doctrine\ORM\NoResultException $e) {
            return $this->_authError(null, self::LOGIN_ERROR);
        }

        $this->_deleteOldLoginAttempts($currentUser);

        if (count($currentUser->getFailedLogins()) >= $this->getMaxAttempts()) {
            return $this->_authError($currentUser, self::TOO_MANY_ATTEMPTS);
        }

        if ($this->passHash != $identity->getPassHash()) {
            $failedLogin = new \User\Model\User\FailedLogin($currentUser);
            $this->_em->persist($failedLogin);
            return $this->_authError($currentUser, self::LOGIN_ERROR);
        }

        $this->_deleteOldLoginAttempts($currentUser, 0);

        return new \Zend_Auth_Result(\Zend_Auth_Result::SUCCESS, $identity);
    }

    /**
     * Get the maximum allowed login attempts
     *
     * @return integer
     */
    public function getMaxAttempts()
    {
        return $this->_maxAttempts;
    }

    /**
     * Set the total number of allowed maximum login attempts
     *
     * @param  integer $total
     * @return User_ModelOld_User_Login *Provides fluid interface*
     */
    public function setMaxAttempts($total)
    {
        $this->_maxAttempts = (int)$total;
        return $this;
    }


    /**
     * Delete any old login attempts
     */
    protected function _deleteOldLoginAttempts($currentUser, $cutoff = null)
    {
        if (null === $cutoff) {
            $cutOffDate = time() - ($this->getMaxAttempts() * 60);
        }
        foreach ($currentUser->getFailedLogins() AS $failedLogin) {
            if ($this->_em->contains($failedLogin) && $failedLogin->date < $cutoff) {
                $this->_em->remove($failedLogin);
            }
            $currentUser->removeFailedLogin($failedLogin);
        }
        $this->_em->flush();
    }

    /**
     * Return an authentication error
     *
     * @param  string $msg
     * @return Zend_Auth_Result
     */
    protected function _authError($currentUser, $msg)
    {
        return new \Zend_Auth_Result(\Zend_Auth_Result::FAILURE, $currentUser, array($msg));
    }
}