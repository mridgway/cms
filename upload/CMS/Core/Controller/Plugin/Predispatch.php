<?php

namespace Core\Controller\Plugin;

/**
 * Predispatch plugin that determines whether a request should be processed as a CMS page or go
 * directly to a controller. If processing as a CMS page, finds the current page id based off of
 * the URL. If a block_id is specified in the request, dispatches directly to the block.
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Controller
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Predispatch extends \Zend_Controller_Plugin_Abstract
{
    /**
    * @var sfServiceContainer
    */
    protected $_serviceContainer = null;

    public function __construct()
    {
        $this->setServiceContainer(\Zend_Registry::get('serviceContainer'));
    }
    
    /**
     * @param Zend_Controller_Request_Abstract $request
     */
    public function dispatchLoopStartup(\Zend_Controller_Request_Abstract $request)
    {
        if (!$request->isDirect() && $request->getRouteId()) {
            $routeRepository = \Zend_Registry::get('doctrine')->getRepository('Core\Model\PageRoute');
            $pageId = $routeRepository->getPageIdForRoute($request->getRouteId(), $request->getSerializedParams());
            if (null === $pageId) {
                throw new \Core\Exception\NotFoundException('Page does not exist.');
            }

            $request->setParam('current_page_id', $pageId);

            if ($request->isPost() && ($route = $request->getParam('block_id'))) {
                $this->_dispatchFormAction($route, $request);
            }
        }
    }

    /**
     * @param integer $blockId
     * @param Zend_Controller_Request_Abstract $request
     */
    protected function _dispatchFormAction($blockId, \Zend_Controller_Request_Abstract $request)
    {
        $em = \Zend_Registry::get('doctrine');
        $block = $em->getRepository('Core\Model\Block')->find($blockId);
        $block->setRequest($request);
        $block->setServiceContainer($this->getServiceContainer());
        $block->process();
        $request->setParam('block_id', null);
    }

    public function getServiceContainer()
    {
        return $this->_serviceContainer;
    }

    public function setServiceContainer(\sfServiceContainer $serviceContainer)
    {
        $this->_serviceContainer = $serviceContainer;
    }
}