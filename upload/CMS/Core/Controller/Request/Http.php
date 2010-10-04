<?php

namespace Core\Controller\Request;

/**
 * A normal Zend HTTP Request with added routeId and isDirect properties
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Controller
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
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