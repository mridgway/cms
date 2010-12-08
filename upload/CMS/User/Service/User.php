<?php

namespace User\Service;

/**
 * Service for users
 *
 * @package     CMS
 * @subpackage  User
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class User extends \Core\Service\AbstractService
{
    /**
     * @param integer $identifier
     */
    public function getUser($identifier)
    {
       return $this->getEntityManager()->getRepository('User\Model\User')->find($identifier);
    }

    /**
     * @return Validation\UserValidator
     */
    public function getUserValidator()
    {
        return new Validation\UserValidator();
    }

    /**
     * @return \User\Form\AdminCreateForm
     */
    public function getAdminCreateForm()
    {
        return new \User\Form\AdminCreateForm();
    }

    /**
     * @param array $data
     * @return User\Model\User
     */
    public function createUser(array $data)
    {
        $validator = $this->getUserValidator();
        if (!$validator->isValid($data)) {
            throw new \Core\Exception\FormException('User data invalid');
        }

        $this->getEntityManager()->beginTransaction();
        try {

            // @todo CHECK PERMISSIONS AS WELL
            if (array_key_exists('group', $data)) {
                $groupRepository = $this->getEntityManager()->getRepository('User\Model\Group');
                if (\is_numeric($data['group'])) {
                    $data['group'] = $groupRepository->find($data['group']);
                } else {
                    $data['group'] = $groupRepository->findOneBySysname($data['group']);
                }
            }

            $user = \User\Model\User::createFromArray($data);
            $this->getEntityManager()->persist($user);

            if (array_key_exists('identity', $data)) {
                $data['identity']['user'] = $user;
                $data['identity']['identifier'] = $data['email'];
                $identity = $this->getIdentityService()->createLocalIdentity($data['identity']);
            }

            $this->getEntityManager()->flush();
            $this->getEntityManager()->commit();
        } catch (\Exception $e) {
            $this->getEntityManager()->rollback();
            throw $e;
        }

        return $user;
    }

    /**
     * @param integer|User\Model\User $user
     * @param array $data
     */
    public function updateUser ($user, array $data)
    {
        if (!$user instanceof \User\Model\User) {
            $user = $this->getUser($user);
        }

        $validator = $this->getUserValidator();
        if (!$validator->isValid($data)) {
            throw new \Core\Exception\FormException('User data invalid');
        }

        try {
            $this->getEntityManager()->beginTransaction();

            $user->fromArray($data);

            if(\array_key_exists('password', $data)) {
                if (!$user->getLocalIdentity()) {
                    $this->getIdentityService()->createLocalIdentity(array(
                        'identifier' => $user->getEmail(),
                        'user' => $user,
                        'password' => $data['password']
                    ));
                } else {
                    $this->getIdentityService()->changePassword($user->getLocalIdentity(), $data['password']);
                }
            }

            if (\array_key_exists('location', $data) && \array_key_exists('zip', $data['location'])) {
                $location = $this->getGeoLocationService()->retrieveGeoLocationByZip($data['location']['zip']);
                $user->setLocation($location);
            }

            $this->getEntityManager()->flush();
            $this->getEntityManager()->commit();
        } catch (\Exception $e) {
            $this->getEntityManager()->rollback();
            throw $e;
        }
    }

    public function deleteUser($user)
    {
        if (!$user instanceof \User\Model\User) {
            $user = $this->getUser($user);
        }

        $currentUser = $this->getAuth()->getIdentity();
        if ($currentUser != $user
                && $currentUser->getGroup()->getSysname() != 'admin'
                && $currentUser->getGroup()->getSysname() != 'root') {
            throw new \Exception('Not Allowed');
        }

        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
    }

    protected $_identityService;
    public function setIdentityService(Identity $s) { $this->_identityService = $s; }
    /** @return User\Service\Identity */
    public function getIdentityService() { return $this->_identityService; }
}