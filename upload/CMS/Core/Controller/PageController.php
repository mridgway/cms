<?php

namespace Core\Controller;

/**
 * Controller for actions on pages
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Controller
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class PageController extends \Zend_Controller_Action
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $_em;

    /**
     * @var \Core\Model\Page
     */
    protected $_page;

    /**
     * @var \sfServiceContainer
     */
    protected $_sc;

    public function init()
    {
        $this->_sc = $this->getInvokeArg('bootstrap')->serviceContainer;
        $this->_em = $this->_sc->getService('doctrine');
        $this->_pageService = $this->_sc->getService('pageService');
    }

    public function viewAction()
    {
        $page = $this->_pageService->getPageIfAllowed($this->getRequest()->getParam('id', false), 'view');
        $pageRenderer = $this->_sc->getService('pageRendererService');
        $content = $pageRenderer->renderPage($page, $this->getRequest());
        $this->getResponse()->setBody($content);
    }

    /**
     * @todo refactor the crap out of this
     */
    public function addBlockAction()
    {
        $page = $this->_pageService->getPageIfAllowed($this->getRequest()->getParam('id', false), 'edit');
        $location = $this->_sc->getService('locationService')->getLocation($this->getRequest()->getParam('location', false));
        $type = $this->getRequest()->getParam('type');

        switch($type) {
            case 'standard':
                $controller = new \Core\Controller\Content\Text();
                $controller->setEntityManager($this->_em);
                $controller->setRequest($this->getRequest());
                $controller->setResponse($this->getResponse());

                $frontend = $controller->addAction();

                $block = null;
                if ($this->getRequest()->isPost() && $frontend->code->id <= 0) {
                    $text = $frontend->html;
                    $block = $this->_sc->getService('staticBlockService')->create($text);
                    $this->_sc->getService('pageService')->addBlock($page, $block, $location);
                    $frontend = new \Core\Model\Frontend\BlockInfo();
                    $frontend->success($block);
                    $frontend->html = $block->render();
                } else {
                    $block = new \stdClass();
                    $block->id = 'new';
                }
                $view = new \Zend_View();
                $view->assign('content', $frontend->html);
                $view->assign('block', $block);
                $edit = $this->getRequest()->getParam('edit', true);
                $view->assign('edit', $edit);
                $view->setBasePath(APPLICATION_ROOT . '/themes/default/layouts');
                $frontend->html = $view->render('partials/block.phtml');
                echo $frontend;
                return;
            case 'shared':
                $types = $this->_sc->getService('textService')->getShared();
                $view = new \Core\Model\View('Core', 'Block/addShared');
                $view->assign('types', $types);
                $view->assign('type', $type);
                $view->assign('id', $page->id);
                $view->assign('location', $location->sysname);
                $frontend = new \Core\Model\Frontend\Simple();
                $frontend->html = $view->render();
                if ($contentId = $this->getRequest()->getParam('content', null)) {
                    $content = $this->_sc->getService('contentService')->getContent($contentId);
                    $block = $this->_sc->getService('staticBlockService')->create($content);
                    $this->_sc->getService('pageService')->addBlock($page, $block, $location);
                    echo new \Core\Model\Frontend\Simple();
                    return;
                }
                echo $frontend;
                return;
            case 'dynamic':
                $types = $this->_sc->getService('dynamicBlockService')->getAddableBlockTypes();
                $view = new \Core\Model\View('Core', 'Block/addDynamic');
                $view->assign('types', $types);
                $view->assign('type', $type);
                $view->assign('id', $page->id);
                $view->assign('location', $location->sysname);
                $frontend = new \Core\Model\Frontend\Simple();
                $frontend->html = $view->render($view->getFile());
                if ($blockId = $this->getRequest()->getParam('blockType', null)) {
                    $block = $this->_sc->getService('dynamicBlockService')->create($blockId);
                    $this->_sc->getService('pageService')->addBlock($page, $block, $location);
                    echo new \Core\Model\Frontend\Simple();
                    return;
                }
                echo $frontend;
                break;
            default:
                throw new \Exception('Invalid block type.');
        }
    }

    /**
    * This function presents a form to add a new page. Upon valid submission of the form, a new page is created.
    */
    public function addAction()
    {
        $this->_pageService->isAllowed('AllPages', 'add');

        $frontend = new \Core\Model\Frontend\Simple();

        $form = $this->_pageService->getDefaultForm();

        $page = null;
        if ($this->getRequest()->isPost()) {
            try {
                $page = $this->_pageService->addPage($this->getRequest()->getPost());
                $form->setObject($page);
                $frontend->success();
                $frontend->data['url'] = $page->getURL();
            } catch (\Core\Exception\FormException $e) {
                $form = $e->getForm();
                $frontend->fail();
            }
        }

        $form->setAction('/direct/page/add');
        $frontend->html = (string)$form;

        $html = $this->getRequest()->getParam('html');
        if (isset($html)) {
            $page = $this->_pageService->getPage($this->getRequest()->getParam('id', false));
            $page->getLayout()->getLocation('main')->addContent($frontend->html);
            $page->getLayout()->assign('page', $page);
            echo $page->getLayout()->render();
        } else {
            echo $frontend;
        }
    }

    /**
    * This function presents a form to edit a page or a template. Upon valid submission of the form, a new page is created.
    */
    public function editAction()
    {
        $page = $this->_pageService->getPageIfAllowed($this->getRequest()->getParam('id', false), 'edit');

        $frontend = new \Core\Model\Frontend\Simple();

        $form = $this->_pageService->getDefaultForm();
        $form->setObject($page);

        if ($this->getRequest()->isPost()) {
            try {
                $this->_pageService->editPage($page, $this->getRequest()->getPost());
                $form->setObject($page);
                $frontend->success();
            } catch(\Core\Exception\FormException $e) {
                $form = $e->getForm();
                $frontend->fail();
            }
        }

        $form->setAction('/direct/page/edit?id=' . $page->getId());
        $frontend->html = (string)$form;
        echo $frontend;
    }

    /**
     * Rearranges the blocks on the page
     */
    public function rearrangeAction()
    {
        $page = $this->_pageService->getPageIfAllowed($this->getRequest()->getParam('id', false), 'edit');

        $receivedfrontendObject = $this->getRequest()->getParam('page', null);
        if (!isset($receivedfrontendObject)) {
            $frontendObject = new \Core\Model\Frontend\Simple();
            die($frontendObject->fail('Page object not sent.'));
        }

        try {
            $receivedfrontendObject = \Zend_Json::decode($receivedfrontendObject, \Zend_Json::TYPE_OBJECT);
            $pageObject = new \stdClass();
            $pageObject->layout = new \stdClass();
            $pageObject->layout->locations = array();
            foreach($receivedfrontendObject->data[0]->locations AS $frontendLocation) {
                $pageObject->layout->locations[] = $frontendLocation->blocks;
            }

            $this->_pageService->update($page, $pageObject);
        } catch (\Exception $e) {
            $frontendObject = new \Core\Model\Frontend\Simple();
            die($frontendObject->fail('Page object not sent.'));
        }

        $frontendObject = new \Core\Model\Frontend\PageInfo();
        echo $frontendObject->success($page);
    }

    /**
     * Gets information on the current page
     */
    public function infoAction()
    {
        $page = $this->_pageService->getPageIfAllowed($this->getRequest()->getParam('id', false), 'edit');

        $frontendObject = new \Core\Model\Frontend\PageInfo();

        echo $frontendObject->success($page);
    }


    /**
     * Deletes the current page
     *
     * @todo message notifying users if content exists on other pages
     * @todo message notifying users where content exists
     */
    public function deleteAction()
    {
        $page = $this->_pageService->getPageIfAllowed($this->getRequest()->getParam('id', false), 'delete');

        $this->_pageService->deletePage($page);

        echo new \Core\Model\Frontend\Simple(0, 'Page deleted successfully.');
    }
}