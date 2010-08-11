<?php
/**
 * Modo CMS
 */

namespace Modo;

/**
 * Description
 *
 * @category   Auth
 * @package    Modo
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Acl.php 243 2010-03-30 20:52:18Z mike $
 */

class Acl extends \Zend_Acl
{
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
                $parent = \Zend_Registry::get('doctrine')->getRepository('\Core\Model\Module\Block')->findOneByClass(get_class($resource));
                $this->addResource($resource, $parent);
            } else if ($resource instanceof \Core\Model\Content) {
                $parent = \Zend_Registry::get('doctrine')->getRepository('\Core\Model\Module\Content')->findOneByClass(get_class($resource));
                $this->addResource($resource, $parent);
            } else {
                throw new \Zend_Acl_Exception('Resource not found.');
            }
        }
        return parent::isAllowed($role, $resource, $privilege);
    }
}