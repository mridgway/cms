<?php

namespace User;

class UserModuleTestCase extends \CMSTestCase
{
    /**
     * @var Session
     */
    protected $session;
    
    /**
     * @var User
     */
    protected $user;

    protected $role;

    protected $testGroup;

    protected $groupRole;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->testGroup = new \User\Model\Group('Test');
        $this->user = new \User\Model\User($this->testGroup, 'test@test.com', 'Modo', 'Developer');
        $this->role = new \User\Model\Acl\Role('test');

        $this->groupRole = new \User\Model\Acl\Role('group');
        $this->testGroup->addRole($this->groupRole);

        $this->session = new \User\Model\Session($this->user, '127.0.0.1', 'test');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
    }
}