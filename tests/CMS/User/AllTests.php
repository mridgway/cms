<?php

namespace User;

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'User\AllTests::main');
}

require_once __DIR__ . '/../../bootstrap.php';

class AllTests
{
    public static function main()
    {
        \PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new \CMSTestSuite('User Tests');

        $suite->addTestSuite('User\Model\GroupTest');
        $suite->addTestSuite('User\Model\SessionTest');
        $suite->addTestSuite('User\Model\UserTest');

        $suite->addTestSuite('User\Model\Identity\LocalTest');

        $suite->addTestSuite('User\Model\Acl\PermissionTest');
        $suite->addTestSuite('User\Model\Acl\ResourceTest');
        $suite->addTestSuite('User\Model\Acl\RoleTest');
        $suite->addTestSuite('User\Model\Acl\RoleAssignment\AbstractRoleAssignmentTest');
        $suite->addTestSuite('User\Model\Acl\RoleAssignment\GroupRoleAssignmentTest');
        $suite->addTestSuite('User\Model\Acl\RoleAssignment\UserRoleAssignmentTest');

        return $suite;
    }
}