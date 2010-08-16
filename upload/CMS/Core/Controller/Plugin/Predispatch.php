<?php
/**
 * Modo CMS
 */

namespace Core\Controller\Plugin;

/**
 * @category   Modo
 * @package    Controller
 * @subpackage Predispatch
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Predispatch.php 244 2010-03-31 13:50:30Z mike $
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
            $pageRoute = $routeRepository->getPageRoute($request->getRouteId(), $request->getSerializedParams());
            if (null === $pageRoute) {
                throw new \Modo\Exception('Page does not exist.');
            }
            $request->setParam('id', $pageRoute->page->id);

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