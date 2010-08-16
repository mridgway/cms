<?php
/**
 * Modo CMS
 */

namespace User\Model\Acl;

/**
 * Description of Identity
 *
 * @category   Model
 * @package    User
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Resource.php 297 2010-05-12 13:34:56Z mike $
 *
 * @Entity
 * @Table(name="Resource")
 *
 * @property int $id
 */
class Resource extends \Core\Model\AbstractModel implements \Zend_Acl_Resource_Interface
{
    /**
     * @var string
     * @Id @Column(type="string", name="sysname", length="50", nullable="true")
     */
    protected $sysname;

    /**
     * @var string
     * @Column(type="string", name="parent", length="50", nullable="true")
     */
    protected $parent;

    public function __construct($resource, $parent = null)
    {
        $this->setSysname($resource);
        $this->setParent($parent);
    }

    public function setSysname($sysname)
    {
        $validator = new \Zend_Validate_StringLength(1, 50);
        if (!$validator->isValid($sysname)) {
            throw new \Core\Model\Exception('Sysname must be between 1 and 50 characters.');
        }
        $this->sysname = $sysname;
        return $this;
    }

    public function setParent($parent = null)
    {
        if (null !== $parent) {
            $validator = new \Zend_Validate_StringLength(1, 50);
            if (!$validator->isValid($parent)) {
                throw new \Core\Model\Exception('Sysname must be between 1 and 50 characters.');
            }
        }
        $this->parent = $parent;
        return $this;
    }

    public function getResourceId() {
        return $this->sysname;
    }
}