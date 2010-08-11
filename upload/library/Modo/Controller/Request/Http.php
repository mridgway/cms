<?php
/**
 * Modo CMS
 */

namespace Modo\Controller\Request;

/**
 * A normal Zend HTTP Request with added routeId and isDirect properties
 *
 * @category   Controller
 * @package    Modo
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Http.php 243 2010-03-30 20:52:18Z mike $
 */

class Http extends \Zend_Controller_Request_Http
{
    /**
     * @var int
     */
    public $_routeId;

    /**
     * Whether we are going directly to a controller or to a page
     *
     * @var boolean
     */
    public $_isDirect = false;

    public function getRouteId()
    {
        return $this->_routeId;
    }

    public function setRouteId($id)
    {
        $this->_routeId = $id;
    }

    public function isDirect()
    {
        return $this->_isDirect;
    }

    public function setDirect()
    {
        $this->_isDirect = true;
    }

    public function getSerializedParams()
    {
        $params = array();
        foreach($this->_params as $key => $param) {
            if ($key == 'controller' || $key == 'action' || $key == 'module') {
                continue;
            }

            // change data type if it's numeric to prevent quotes around params
            if (is_numeric($param)) {
                $params[$key] = (int) $param;
            } else {
                $params[$key] = $param;
            }
        }
        unset($params['routeId']);

        if (empty($params)) {
            return null;
        }
        
        return $this->serializeParams($params);
    }

    public function serializeParams($params)
    {
        return serialize($params);
    }

    public function hasParams()
    {
        return (count($this->_params) > 1);
    }
}