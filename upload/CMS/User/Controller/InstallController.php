<?php

namespace User\Controller;

/**
 * Installs the user module into the CMS
 *
 * @package     CMS
 * @subpackage  User
 * @category    Controller
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class InstallController extends \Core\Controller\AbstractInstallController
{

    protected $moduleName = 'User';

    protected $classes = array(
    );

    public function installAction()
    {
        echo '<b>Registering Module...</b><br/>';
        $this->registerModule();
        echo '<b>Module registered.</b><br/><br>';

        echo 'Creating default user groups and roles...<br/>';
        ob_flush();
        $this->_addDefaultGroupsAndRoles();
        echo 'Default user groups and roles created.<br/>';

        echo '<h3>User Module Installed</h3>';
        ob_flush();
    }

    public function _addDefaultGroupsAndRoles()
    {
        $guestGroup = new \User\Model\Group('guest', 'Guest');
        $this->_em->persist($guestGroup);
        $adminGroup = new \User\Model\Group('admin', 'Admin');
        $this->_em->persist($adminGroup);
        $rootGroup = new \User\Model\Group('root', 'Root');
        $this->_em->persist($rootGroup);

        $publicRole = new \User\Model\Acl\Role('public');
        $this->_em->persist($publicRole);
        $adminRole = new \User\Model\Acl\Role('global');
        $this->_em->persist($adminRole);

        $guestGroup->addRole($publicRole);
        $adminGroup->addRole($adminRole);
        $rootGroup->addRole($adminRole);

        // The standard permissions
        $customResources = array(
            'AdminMenu' => new \User\Model\Acl\Resource('AdminMenu')
        );
        $permissions = array(
            new \User\Model\Acl\Permission($adminRole),
            new \User\Model\Acl\Permission(null, null, 'view'),
            new \User\Model\Acl\Permission(null, $customResources['AdminMenu'], 'view', false),
            new \User\Model\Acl\Permission($adminRole, $customResources['AdminMenu'], 'view', true)
        );

        foreach ($customResources AS $r) {
            $this->_em->persist($r);
        }
        foreach ($permissions AS $p) {
            $this->_em->persist($p);
        }

        $mododevUser = new \User\Model\User($rootGroup, 'developer@mododesigngroup.com', 'Modo', 'Developer');
        $this->_em->persist($mododevUser);
        $mododevLogin = new \User\Model\Identity\Local('developer@mododesigngroup.com', 'testing', $mododevUser);
        $this->_em->persist($mododevLogin);

        $this->_em->flush();
    }
}