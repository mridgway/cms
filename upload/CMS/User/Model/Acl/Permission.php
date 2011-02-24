<?php

namespace User\Model\Acl;

/**
 * Representatin of a permission to a resource that is given to a role
 *
 * @package     CMS
 * @subpackage  User
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity
 * @Table(name="permission")
 *
 * @property int $id
 * @var User\Model\Acl\Role $role
 * @var string $resource
 * @var Core\Model\Module\Privilege $privilege
 * @var boolean $allow
 */
class Permission extends \Core\Model\AbstractModel
{
    /**
     * @var integer
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var User\Model\Acl\Role
     * @Column(type="string", name="role", length="50", nullable="true")
     */
    protected $role;

    /**
     * @var string
     * @Column(type="string", name="resource", length="50", nullable="true")
     */
    protected $resource;

    /**
     * @var Core\Model\Module\Privilege
     * @Column(type="string", name="privilege", length="50", nullable="true")
     */
    protected $privilege;

    /**
     * @var boolean
     * @Column(type="boolean", name="allow", nullable="false")
     */
    protected $allow;

    /**
     * @var string
     * @Column(type="string", name="assertion", length="100", nullable="true")
     */
    protected $assertion = null;

    /**
     * @param mixed $role
     * @param string $resource
     * @param mixed $privilege
     * @param boolean $allow
     */
    public function __construct($role = null, $resource = null, $privilege = null, $allow = true, $assertion = null)
    {
        $this->setRole($role);
        $this->setResource($resource);
        $this->setPrivilege($privilege);
        $this->setAllow($allow);
        $this->setAssertion($assertion);
    }

    /**
     * @param string $role
     * @return Permission
     */
    public function setRole($role = null)
    {
        // accept either a string or a Zend_Acl_Resource_Interface
        $role = ($role instanceof \Zend_Acl_Role_Interface) ? $role->getRoleId() : $role;

        if ($role != null) {
            $validator = new \Zend_Validate_StringLength(0, 50);
            if (!$validator->isValid($role)) {
                throw new \Core\Model\Exception('Role name must be between 0 and 50 characters.');
            }
        }
        $this->role = $role;
        return $this;
    }

    /**
     * @param string $resource
     * @return Permission
     */
    public function setResource($resource = null)
    {
        // accept either a string or a Zend_Acl_Resource_Interface
        $resource = ($resource instanceof \Zend_Acl_Resource_Interface) ? $resource->getResourceId() : $resource;

        if ($resource != null) {
            $validator = new \Zend_Validate_StringLength(0, 50);
            if (!$validator->isValid($resource)) {
                throw new \Core\Model\Exception('Resource name must be between 0 and 50 characters.');
            }
        }
        $this->resource = $resource;
        return $this;
    }

    /**
     * @param string $privilege
     * @return Permission
     */
    public function setPrivilege($privilege = null)
    {
        $privilege = ($privilege instanceof \Core\Model\Module\Privilege) ? $privilege->getSysname() : $privilege;

        if ($privilege != null) {
            $validator = new \Zend_Validate_StringLength(0, 50);
            if (!$validator->isValid($privilege)) {
                throw new \Core\Model\Exception('Privilege name must be between 0 and 50 characters.');
            }
        }
        $this->privilege = $privilege;
        return $this;
    }

    /**
     * @param boolean $allow
     * @return Permission
     */
    public function setAllow($allow)
    {
        if (!is_bool($allow)) {
            throw new \Core\Model\Exception('Allow must be a boolean.');
        }
        $this->allow = $allow;
        return $this;
    }

    /**
     * @todo validation of the actual assertion class (make sure it implements the correct interface
     * @param string $assertion
     * @return Permission
     */
    public function setAssertion($assertion = null)
    {
        if ($assertion != null) {
            $validator = new \Zend_Validate_StringLength(0, 50);
            if (!$validator->isValid($assertion)) {
                throw new \Core\Model\Exception('Assertion name must be between 0 and 200 characters.');
            }
        }
        $this->assertion = $assertion;
        return $this;
    }
}