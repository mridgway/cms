<?php

namespace Core\Controller;

/**
 * Controller for handling errors
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Controller
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class ErrorController extends \Zend_Controller_Action
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $_em;

    public function init()
    {
        $this->_em = \Zend_Registry::get('em');
    }

    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');

        $this->getRequest()->setParam('exception', $errors->exception);
        
        switch ($errors->type) {
            case \Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case \Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:

                $route = $this->_em->getRepository('Core\Model\Route')->findOneBySysname('404');
                $pageRoutes = $route->getPageRoutes();

                $pageRenderer = new \Core\Service\PageRenderer($this->_em, $this->getRequest());
                $content = $pageRenderer->renderPage($pageRoutes[0]->getPage(), $request);
                $this->getResponse()->setBody($content);

                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                break;
        }
    }


}

