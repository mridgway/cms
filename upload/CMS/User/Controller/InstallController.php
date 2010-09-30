<?php

namespace User\Controller;

/**
 * Installs the user module into the CMS
 *
 * @package     CMS
 * @subpackage  User
 * @category    Controller
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class InstallController extends \Core\Controller\AbstractInstallController
{

    protected $moduleName = 'User';

    protected $classes = array(
        'User\Model\User',
        'User\Model\Identity',
        'User\Model\Session',
        'User\Model\Group',

        'User\Model\Acl\Role',
        'User\Model\Acl\Permission',
        'User\Model\Acl\Privilege',
        'User\Model\Acl\Resource',
        'User\Model\Acl\RoleAssignment\AbstractRoleAssignment',
        'User\Model\Acl\RoleAssignment\UserRoleAssignment',
        'User\Model\Acl\RoleAssignment\GroupRoleAssignment'
    );

    public function installAction()
    {
        echo '<h3>Installing User Module</h3>';
        echo '<b>Creating tables...</b><br/>';
        ob_flush();
        $this->createSchema();
        echo '<b>Tables created.</b><br/><br>';

        echo '<b>Registering Module...</b><br/>';
        $this->registerModule();
        echo '<b>Module registered.</b><br/><br>';

        echo 'Adding Login Block to Homepage...<br/>';
        ob_flush();
        $this->_addBlockToHomePage();
        echo 'Login block added to homepage<br/>';

        echo 'Creating default user groups and roles...<br/>';
        ob_flush();
        $this->_addDefaultGroupsAndRoles();
        echo 'Default user groups and roles created.<br/>';

        echo '<h3>User Module Installed</h3>';
        ob_flush();
    }

    public function _addBlockToHomePage()
    {
        $loginView = $this->module->getBlockType('LoginForm')->getView('default');

        $block = new \User\Block\Form\Login($loginView);

        $pageRoutes = $this->_em->getRepository('Core\Model\Route')->findOneBySysname('home')->getPageRoutes();
        /* @var $page \Core\Model\Page */
        $page = $pageRoutes[0]->getPage();

        $location = $this->_em->getRepository('Core\Model\Layout\Location')->find('right');
        $page->addBlock($block, $location, 0);

        $this->_em->flush();
    }

    public function _addDefaultGroupsAndRoles()
    {
        $guestGroup = new \User\Model\Group('Guest');
        $this->_em->persist($guestGroup);
        $userGroup = new \User\Model\Group('User');
        $this->_em->persist($userGroup);
        $adminGroup = new \User\Model\Group('Admin');
        $this->_em->persist($adminGroup);

        $publicRole = new \User\Model\Acl\Role('public');
        $this->_em->persist($publicRole);
        $adminRole = new \User\Model\Acl\Role('global');
        $this->_em->persist($adminRole);

        $guestGroup->addRole($publicRole);
        $adminGroup->addRole($adminRole);

        // The standard permissions
        $customResources = array(
            'Page2' => new \User\Model\Acl\Resource('Page.2', 'AllPages'),
            'Content1' => new \User\Model\Acl\Resource('Content.1', 'Core.Content.Text'),
            'AdminMenu' => new \User\Model\Acl\Resource('AdminMenu')
        );
        $permissions = array(
            new \User\Model\Acl\Permission($adminRole),
            new \User\Model\Acl\Permission(null, null, 'view'),
            new \User\Model\Acl\Permission($publicRole, $customResources['Page2'], 'view', false),
            new \User\Model\Acl\Permission($publicRole, $customResources['Content1'], 'view', false),
            new \User\Model\Acl\Permission(null, $customResources['AdminMenu'], 'view', false),
            new \User\Model\Acl\Permission($adminRole, $customResources['AdminMenu'], 'view', true)
        );

        foreach ($customResources AS $r) {
            $this->_em->persist($r);
        }
        foreach ($permissions AS $p) {
            $this->_em->persist($p);
        }

        $mododevUser = new \User\Model\User($adminGroup, 'mododev@mododesigngroup.com', 'Modo', 'Developer');
        $this->_em->persist($mododevUser);
        $mododevLogin = new \User\Model\Identity('local', 'mododev', $mododevUser);
        $mododevLogin->setPassHash('testing');
        $this->_em->persist($mododevLogin);

        $this->_em->flush();
    }
}