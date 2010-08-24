<?php

namespace Core\Controller\Router;

/**
 * Parses URIs for params to be used in the dispatcher
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Controller
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class Rewrite extends \Zend_Controller_Router_Rewrite
{
    public function route(\Zend_Controller_Request_Abstract $request)
    {
        $request = parent::route($request);
        $route = $this->getCurrentRoute();
        if ($route instanceof \Core\Model\Route) {
            $request->setRouteId($route->id);
            if ($route->isDirect) {
                $request->setDirect();
            }
        }
        return $request;
    }
}