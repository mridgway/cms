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

    protected $_logs= array();

    public function init()
    {
        $this->_em = \Zend_Registry::get('doctrine');
    }

    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');

        $this->getRequest()->setParam('exception', $errors->exception);
        $sc = $this->getInvokeArg('bootstrap')->serviceContainer;

        switch ($errors->type) {
            case \Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case \Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
            case \Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
                $this->notFoundException($errors->exception);
                break;
            default:
                switch (\get_class($errors->exception)) {
                    case 'Core\Exception\NotFoundException':
                        $this->notFoundException($errors->exception);
                        break;
                    case 'Core\Exception\PermissionException':
                        $this->permissionError($errors->exception);
                        break;
                    default:
                        $this->otherException($errors->exception);
                        break;
                }
                break;
        }
    }

    protected function permissionError($exception)
    {
        if (!$this->getServiceContainer()->getService('auth')->hasIdentity()) {
            // @todo: forward back to original page on successful login
            $this->_redirect('/login');
        }
        // Render the 403 error page
        $route = $this->_em->getRepository('Core\Model\Route')->findOneBySysname('403');
        if ($route && count($route->getPageRoutes())) {
            $pageRoutes = $route->getPageRoutes();
            $pageRenderer = $this->getServiceContainer()->getService('pageRendererService');
            $content = $pageRenderer->renderPage($pageRoutes[0]->getPage(), $this->getRequest());
            $this->getResponse()->setBody($content);
        }

        // 403 error -- permission denied
        $this->getResponse()->setHttpResponseCode(403);

        if ($this->getLogger('denied')) {
            $userId = $sc->getService('auth')->getIdentity();
            $this->getLogger('denied')->log("Permission denied for user $userId: " . $_SERVER['REQUEST_URI'], \Zend_Log::NOTICE);
        }
    }

    protected function notFoundException($exception)
    {
        // Render the 404 error page
        $route = $this->_em->getRepository('Core\Model\Route')->findOneBySysname('404');
        if ($route && count($route->getPageRoutes())) {
            $pageRoutes = $route->getPageRoutes();
            $pageRenderer = $this->getServiceContainer()->getService('pageRendererService');
            $content = $pageRenderer->renderPage($pageRoutes[0]->getPage(), $this->getRequest());
            $this->getResponse()->setBody($content);
        }

        // 404 error -- controller or action not found
        $this->getResponse()->setHttpResponseCode(404);

        if ($this->getLogger('notfound')) {
            $this->getLogger('notfound')->log('Page not found: ' . $_SERVER['REQUEST_URI'], \Zend_Log::INFO);
        }
    }

    protected function otherException($exception)
    {
        // application error
        $this->getResponse()->setHttpResponseCode(500);
        $body = '500 Internal Server Error';
        if ($this->getLogger('error')) {
            $this->getLogger('error')->log($this->getExceptionString($exception), \Zend_Log::ERR);
        }
        $this->getResponse()->setBody($body);
    }

    protected function getExceptionString($exception)
    {
        $url = $_SERVER['REQUEST_URI'];
        $exceptionClass = get_class($exception);
        $string = <<<EOD

Requested Uri
-----
{$url}

Class
-----
{$exceptionClass}

File
-----
{$exception->getFile()}

Line
-----
{$exception->getLine()}

Message
-----
{$exception->getMessage()}

Trace
-----
{$exception->getTraceAsString()}
EOD;

        return $string;
    }

    /**
     * @return Zend_Log
     */
    protected function getLogger($sysname)
    {
        try {
            $config = $this->getServiceContainer()->getService('config')->log->{$sysname};
            if ($config && $config->enabled) {
                if (!isset($this->_logs[$sysname])) {
                    $logger = new \Zend_Log();
                    if ($config->path) {
                        $logger->addWriter(new \Zend_Log_Writer_Stream($config->path, 'a+'));
                    }
                    if ($config->email) {
                        $mail = new \Zend_Mail();
                        $mail->setFrom('errors@greenhomeguide.com')
                             ->addTo($config->email);
                        $writer = new \Zend_Log_Writer_Mail($mail);
                        $writer->setSubjectPrependText('GHG Error');
                        $logger->addWriter($writer);
                    }
                    $this->_logs[$sysname] = $logger;
                }
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return null;
        }
        return $this->_logs[$sysname];
    }

    protected function getServiceContainer()
    {
        return $this->getInvokeArg('bootstrap')->serviceContainer;
    }
}