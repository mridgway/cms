<?php

namespace User;

class Bootstrap extends \Zend_Application_Module_Bootstrap
{

    public function _initAuth()
    {
        \Modo\Auth::getInstance()->setStorage(new \Modo\Auth\Storage\Doctrine(\Zend_Registry::get('doctrine')));
    }

    public function _initAcl()
    {
        $this->bootstrap('auth');
        $acl = new \Modo\Acl;
        /* @var $em EntityManager */
        $em = \Zend_Registry::get('doctrine');

        // Wrap in a try/catch in case the database hasn't been set up yet.
        try {
            // Set roles
            $roles = $em->getRepository('User\Model\Acl\Role')->findAll();
            foreach ($roles AS $role) {
                $acl->addRole($role);
            }
            // Create a role that inherits from the user's roles (allows multiple roles per user)
            $acl->addRole(\Modo\Auth::getInstance()->getIdentity(), \Modo\Auth::getInstance()->getIdentity()->getRoles());

            // Set resources
            $acl->addResource('AllModules');
            $acl->addResource('AllPages');
            $modules = $em->getRepository('Core\Model\Module')->findAll();
            foreach ($modules AS $module) {
                $acl->addResource($module, 'AllModules');
                foreach ($module->getBlockTypes() AS $blockType) {
                    $acl->addResource($blockType, $module);
                }
                foreach ($module->getContentTypes() AS $contentType) {
                    $acl->addResource($contentType, $module);
                }
            }
            $resources = $em->getRepository('User\Model\Acl\Resource')->findAll();
            foreach ($resources AS $resource) {
                if ($resource->getParent()) {
                    $acl->addResource($resource->getSysname(), $resource->getParent());
                } else {
                    $acl->addResource($resource->getSysname());
                }
            }

            // Set permissions
            $permissions = $em->getRepository('User\Model\Acl\Permission')->findAll();
            foreach ($permissions AS $permission) {
                if (null == $permission->getResource() || $acl->has($permission->getResource())) {
                    $assert = ($permission->getAssertion() == null) ? null : new $permission->getAssertion();
                    if ($permission->getAllow() == true) {
                        $acl->allow($permission->getRole(), $permission->getResource(), $permission->getPrivilege(), $assert);
                    } else {
                        $acl->deny($permission->getRole(), $permission->getResource(), $permission->getPrivilege(), $assert);
                    }
                }
            }
            \Modo\Auth::getInstance()->getIdentity()->setAcl($acl);
        } catch (\PDOException $e) {
            //throw $e;
        }

        \Zend_Registry::set('acl', $acl);
    }

}