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
class Identity extends \Core\Service\AbstractService
{

    public function getLocalIdentityValidator()
    {
        return new Validation\Identity\LocalValidator();
    }

    public function createLocalIdentity($data)
    {
        return $this->_createIdentity(
                'User\Model\Identity\Local',
                $this->getLocalIdentityValidator(),
                $data);
    }

    public function createOpenID($data)
    {
        return $this->_createIdentity('User\Model\Identity\OpenID', null, $data);
    }

    protected function _createIdentity($className, $validator, $data)
    {
        if ($validator) {
            $validator = $this->getLocalIdentityValidator();
            if (!$validator->isValid($data)) {
                throw new \Core\Exception\FormException('User data invalid');
            }
        }

        $this->getEntityManager()->beginTransaction();
        try {

            $identity = $className::createFromArray($data);
            $this->getEntityManager()->persist($identity);

            $this->getEntityManager()->flush();
            $this->getEntityManager()->commit();
        } catch (\Exception $e) {
            $this->getEntityManager()->rollback();
            throw $e;
        }
    }

    public function changePassword(\User\Model\Identity\Local $identity, $password)
    {
        if ($password == '') {
            return;
        }
        $identity->setPassword($password);
    }

    public function removeIdentity($identity)
    {
        if (!$this->ensureIdentity($identity)) {
            throw new \Exception('Identity invalid.');
        }

        $this->getEntityManager()->remove($identity);
        $this->getEntityManager()->flush();
    }

    public function ensureIdentity(&$identity)
    {
        if ($identity instanceof \User\Model\Identity) {
            return $identity;
        }

        $identityRepository = $this->getEntityManager()->getRepository('User\Model\Identity');
        $identity = $identityRepository->find($identity);
    }
}