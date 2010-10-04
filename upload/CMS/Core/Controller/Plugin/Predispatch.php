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
     * @param Zend_Controller_Request_Abstract $request
     */
    public function dispatchLoopStartup(\Zend_Controller_Request_Abstract $request)
    {
        if (!$request->isDirect()) {
            $routeRepository = \Zend_Registry::get('doctrine')->getRepository('Core\Model\PageRoute');
            $pageId = $routeRepository->getPageIdForRoute($request->getRouteId(), $request->getSerializedParams());
            if (null === $pageId) {
                throw new \Exception('Page does not exist.');
            }

            $request->setParam('id', $pageId);

            if ($request->isPost() && !is_null($route = $request->getParam('block_id'))) {
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
        $block->setEntityManager($em);
        $block->process();
        $request->setParam('block_id', null);
    }
}