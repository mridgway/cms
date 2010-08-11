<?php
/**
 * Modo CMS
 */

namespace Modo\Controller\Router;

/**
 * Parases URIs for params to be used in the dispatcher
 *
 * @category   Controller
 * @package    Modo
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Rewrite.php 40 2009-12-28 15:01:46Z mike $
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