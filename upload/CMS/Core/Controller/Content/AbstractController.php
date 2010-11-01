<?php

namespace Core\Controller\Content;

/**
 * Base class for content controllers
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Controller
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
abstract class AbstractController implements ControllerInterface
{

    /**
     *
     * @var \Zend_Controller_Request_Http
     */
    protected $_request;

    /**
     *
     * @var \Zend_Controller_Response_Http
     */
    protected $_response;

    /**
     * @var sfServiceContainer
     */
    protected $_sc;

    /**
     * Sets the request object
     *
     * @param Zend_Controller_Request_Http $request
     */
    public function setRequest(\Zend_Controller_Request_Http $request)
    {
        $this->_request = $request;
    }

    /**
     * Gets the request object
     *
     * @return Zend_Controller_Request_Http
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * Sets the response object
     *
     * @param Zend_Controller_Response_Http $response
     */
    public function setResponse(\Zend_Controller_Response_Http $response)
    {
        $this->_response = $response;
    }

    /**
     * Gets the request object
     *
     * @return \Zend_Controller_Response_Http
     */
    public function getResponse()
    {
        return $this->_response;
    }

    /**
     * Gets the service container
     *
     * @return \sfServiceContainer
     */
    public function getServiceContainer()
    {
        return $this->_sc;
    }

    /**
     * Sets the service container
     */
    public function setServiceContainer(\sfServiceContainer $serviceContainer)
    {
        $this->_sc = $serviceContainer;
    }
}