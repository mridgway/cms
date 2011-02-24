<?php

namespace Core\Controller;

/**
 * Controller for actions on content
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Controller
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class ContentController extends \Zend_Controller_Action
{
    /**
     * @var sfServiceContainer
     */
    protected $_sc;

    public function init()
    {
        $this->_sc = $this->getInvokeArg('bootstrap')->serviceContainer;
    }

    public function addAction()
    {
        $contentService = $this->_sc->getService('contentService');

        // dispatch to content controller
        try {
            $contentType = $this->getRequest()->getParam('type');
            $frontend = $contentService->dispatchContentAction($contentType, 'addAction', $this->getRequest());
        } catch (\Exception $e) {
            $frontend = new \Core\Model\Frontend\Simple();
            echo $frontend->fail($e->getMessage());
        }

        // Attach jquery scripts
        $view = new \Zend_View();
        \ZendX_JQuery::enableView($view);
        $frontend->html .= (string) $view->jQuery()
            ->setRenderMode(\ZendX_JQuery::RENDER_JAVASCRIPT | \ZendX_JQuery::RENDER_JQUERY_ON_LOAD);

        echo $frontend;
    }

    public function authorAction()
    {
        $contentService = $this->_sc->getService('contentService');
        $authors = $contentService->getAvailableAuthors($this->_getParam('term'));

        $authorsArray = array();
        foreach($authors AS $author) {
            $object = new \stdClass();
            $object->id = $author->getId();
            $object->label = $author->getFirstName() . ' ' . $author->getLastName();
            $object->value = $object->label;
            $authorsArray[] = $object;
        }

        echo \Zend_Json::encode($authorsArray);
    }
}

