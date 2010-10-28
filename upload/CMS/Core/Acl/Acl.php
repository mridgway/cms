<?php

namespace Core\Acl;

/**
 * Overrides Zend_Acl to support dynamically loading resources from the database
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Acl
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Acl extends \Zend_Acl
{
    /**
     * @var \Core\Module\Registry
     */
    protected $_moduleRegistry;

    public function __construct()
    {
    }

    /**
     * @todo remove query for parent
     *
     * @param <type> $role
     * @param <type> $resource
     * @param <type> $privilege
     * @return <type>
     */
    public function isAllowed($role = null, $resource = null, $privilege = null)
    {
        if (!$this->has($resource)) {
            if ($resource instanceof \Core\Model\AbstractPage) {
                $this->addResource($resource, 'AllPages');
            } else if ($resource instanceof \Core\Model\Block) {
                $parent = $this->getModuleRegistry()->getDatabaseStorage()->getBlockTypeByClass(get_class($resource));
                $this->addResource($resource, $parent);
            } else if ($resource instanceof \Core\Model\Content) {
                $parent = $this->getModuleRegistry()->getDatabaseStorage()->getContentTypeByClass(get_class($resource));
                $this->addResource($resource, $parent);
            } else {
                throw new \Zend_Acl_Exception('Resource \'' . $resource . '\' not found.');
            }
        }
        return parent::isAllowed($role, $resource, $privilege);
    }

    public function getModuleRegistry()
    {
        return $this->_moduleRegistry;
    }

    public function setModuleRegistry($moduleRegistry)
    {
        $this->_moduleRegistry = $moduleRegistry;
    }
}