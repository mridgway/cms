<?php

namespace User;

/**
 * Bootstraps required resources for the user module
 *
 * @package     CMS
 * @subpackage  User
 * @category    Bootstrap
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Bootstrap extends \ZendX\Application53\Application\Module\Bootstrap
{

    public function _initAuth()
    {
        \Core\Auth\Auth::getInstance()->setStorage(new \Core\Auth\Storage\Doctrine(\Zend_Registry::get('doctrine')));
    }

    public function _initAcl()
    {
        $sf = $this->getApplication()->getResource('serviceContainer');

        $this->bootstrap('auth');
        $moduleRegistry = $sf->getService('moduleRegistry');
        $acl = new \Core\Acl\Acl;
        $acl->setModuleRegistry($moduleRegistry);
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
            $acl->addRole(\Core\Auth\Auth::getInstance()->getIdentity(), \Core\Auth\Auth::getInstance()->getIdentity()->getRoles());

            // Set resources
            $acl->addResource('AllModules');
            $acl->addResource('AllPages');
            $modules = $moduleRegistry->getDatabaseStorage()->getModules();
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
            \Core\Auth\Auth::getInstance()->getIdentity()->setAcl($acl);
        } catch (\PDOException $e) {
            //throw $e;
        }

        \Zend_Registry::set('acl', $acl);
        $sf->setService('acl', $acl);
    }

}