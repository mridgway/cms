<?php

namespace Core\Service;

/**
 * Service for views
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Content extends \Core\Service\AbstractContent
{
    /**
     * @var \Core\Module\Registry
     */
    protected $_moduleRegistry;

    /**
     * @var \sfServiceContainer
     */
    protected $_sc;

    public function getContent($id)
    {
        return $this->_em->getReference('Core\Model\Content', $id);
    }

    public function dispatchContentAction($content, $action, $request)
    {
        $controller = $this->getContentControllerObject($content);
        if (!method_exists($controller, $action)) {
            throw new \Exception('Content controller/action does not exist.');
        }
        $controller->setServiceContainer($this->getServiceContainer());
        $controller->setRequest($request);
        return $controller->$action();
    }

    public function getContentControllerObject($content)
    {
        $controllerName = $this->getContentController($content);

        if(null === $controllerName) {
            throw new \Exception('Controller is not specified for content of type ' . $content . '  Check the module.ini file.');
        }

        return new $controllerName;
    }

    /**
     * @todo optimize this
     * @param Content|string $content
     * @return string
     */
    public function getContentController($content)
    {
        $modules = $this->getModuleRegistry()->getDatabaseStorage()->getModules();
        foreach ($modules AS $module) {
            foreach($module->contentTypes AS $type) {
                if ((is_string($content) && $type->discriminator == $content)
                        || (is_object($content) && $type->class == get_class($content))) {
                    return $type->controller;
                }
            }
        }
        return null;
    }

    public function getAvailableAuthors($term = null)
    {
        $qb = $this->getEntityManager()->getRepository('User\Model\User')->createQueryBuilder('u');
        $qb->innerJoin('u.group', 'g');
        $qb->where('g.sysname != :groupSysname');
        $qb->setParameter('groupSysname', 'guest');

        if (null !== $term) {
            $qb->andWhere('CONCAT(u.firstName, CONCAT(:nameSeparator, u.lastName)) LIKE :term');
            $qb->setParameter('nameSeparator', ' ');
            $qb->setParameter('term', $term . '%');
        }
        
        return $qb->getQuery()->getResult();
    }

    public function getModuleRegistry()
    {
        return $this->_moduleRegistry;
    }

    public function setModuleRegistry(\Core\Module\Registry $moduleRegistry)
    {
        $this->_moduleRegistry = $moduleRegistry;
    }

    public function getServiceContainer()
    {
        return $this->_sc;
    }

    public function setServiceContainer(\sfServiceContainer $serviceContainer)
    {
        $this->_sc = $serviceContainer;
    }
}