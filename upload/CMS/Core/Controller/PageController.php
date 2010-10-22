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
     * @var \Core\Service\PageService
     */
    protected $_pageService;

    /**
     * @var \Core\Model\Page
     */
    protected $_page;

    /**
     * @var \sfServiceContainer
     */
    protected $_sf;

    public function init()
    {
        $this->_sf = $this->getInvokeArg('bootstrap')->serviceContainer;

        $this->_em = $this->_sf->getService('doctrine');

        $this->_pageService = $this->_sf->getService('pageService');

        if ($this->getRequest()->getActionName() != 'add') {
            if (!$pageId = $this->getRequest()->getParam('id', false)) {
                throw new \Exception('Page not set.');
            }
            $this->_page = $this->_pageService->getPage($pageId);
        }
    }

    public function viewAction()
    {
        if (!\Core\Auth\Auth::getInstance()->getIdentity()->isAllowed($this->_page, 'view')) {
            throw new \Exception('Not allowed to view page.');
        }

        $pageRenderer = new \Core\Service\PageRenderer($this->_em);
        $content = $pageRenderer->renderPage($this->_page, $this->getRequest());
        $this->getResponse()->setBody($content);
    }

    /**
     * @todo refactor the crap out of this
     */
    public function addBlockAction()
    {
        if (!\Core\Auth\Auth::getInstance()->getIdentity()->isAllowed($this->_page, 'edit')) {
            throw new \Exception('Not allowed to edit page.');
        }
        if (!($location = $this->getRequest()->getParam('location'))){
            throw new \Exception('Invalid location.');
        }
        if (!($location = $this->_em->getRepository('Core\Model\Layout\Location')->findOneBySysname($location))) {
            throw new \Exception('Invalid location.');
        }
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
                    $frontend = new \Core\Model\Frontend\BlockInfo();
                    $view = \Core\Module\Registry::getInstance()->getDatabaseStorage()->getModule('Core')->getContentType('Text')->getView('default');
                    $block = new \Core\Model\Block\StaticBlock($text, $view);
                    $this->_page->addBlock($block, $location);
                    $this->_em->persist($block);
                    $this->_em->flush();
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
                $types = $this->_em->getRepository('Core\Model\Content\Text')->findSharedText();
                $view = new \Core\Model\View('Core', 'Block/addShared');
                $view->assign('types', $types);
                $view->assign('type', $type);
                $view->assign('id', $this->_page->id);
                $view->assign('location', $location->sysname);
                $frontend = new \Core\Model\Frontend\Simple();
                $frontend->html = $view->render();
                if ($contentId = $this->getRequest()->getParam('content', null)) {
                    $content = $this->_em->getReference('Core\Model\Content', $contentId);
                    $view = \Core\Module\Registry::getInstance()->getDatabaseStorage()->getModule('Core')->getContentType('Text')->getView('default');
                    $block = new \Core\Model\Block\StaticBlock($content, $view);
                    $this->_page->addBlock($block, $location);
                    $this->_em->persist($block);
                    $this->_em->flush();
                    echo new \Core\Model\Frontend\Simple();
                    return;
                }
                echo $frontend;
                return;
            case 'dynamic':
                $types = $this->_em->getRepository('Core\Model\Module\BlockType')->findAddableBlockTypes();
                $view = new \Core\Model\View('Core', 'Block/addDynamic');
                $view->assign('types', $types);
                $view->assign('type', $type);
                $view->assign('id', $this->_page->id);
                $view->assign('location', $location->sysname);
                $frontend = new \Core\Model\Frontend\Simple();
                $frontend->html = $view->render($view->getFile());
                if ($blockId = $this->getRequest()->getParam('blockType', null)) {
                    $blockType = $this->_em->find('Core\Model\Module\BlockType', $blockId);
                    $view = $blockType->getView('default');
                    $block = $blockType->createInstance(array($view));
                    $this->_page->addBlock($block, $location);
                    $this->_em->persist($block);
                    $this->_em->flush();
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
        if (!\Core\Auth\Auth::getInstance()->getIdentity()->isAllowed('AllPages', 'add')) {
            throw new \Exception('Not allowed to add page.');
        }

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
            $this->_page->getLayout()->getLocation('main')->addContent($frontend->html);
            $this->_page->getLayout()->assign('page', $this->_page);
            echo $this->_page->getLayout()->render();
        } else {
            echo $frontend;
        }
    }

    /**
    * This function presents a form to edit a page or a template. Upon valid submission of the form, a new page is created.
    */
    public function editAction()
    {
        if (!\Core\Auth\Auth::getInstance()->getIdentity()->isAllowed($this->_page, 'edit')) {
            throw new \Exception('Not allowed to edit page.');
        }

        $frontend = new \Core\Model\Frontend\Simple();

        $form = $this->_pageService->getDefaultForm();
        $form->setObject($this->_page);

        if ($this->getRequest()->isPost()) {
            try {
                $this->_pageService->editPage($this->_page, $this->getRequest()->getPost());
                $form->setObject($this->_page);
                $frontend->success();
            } catch(\Core\Exception\FormException $e) {
                $form = $e->getForm();
                $frontend->fail();
            }
        }

        $form->setAction('/direct/page/edit?id=' . $this->_page->getId());
        $frontend->html = (string)$form;

        $html = $this->getRequest()->getParam('html');
        if (isset($html)) {
            $this->_page->getLayout()->getLocation('main')->addContent($frontend->html);
            $this->_page->getLayout()->assign('page', $this->_page);
            echo $this->_page->getLayout()->render();
        } else {
            echo $frontend;
        }
    }

    /**
     * Rearranges the blocks on the page
     */
    public function rearrangeAction()
    {
        if (!\Core\Auth\Auth::getInstance()->getIdentity()->isAllowed($this->_page, 'edit')) {
            throw new \Exception('Not allowed to edit page.');
        }

        $receivedfrontendObject = $this->getRequest()->getParam('page', null);
        if (!isset($receivedfrontendObject)) {
            $frontendObject = new \Core\Model\Frontend\Simple();
            die($frontendObject->fail('Page object not sent.'));
        }

        try {
            $receivedfrontendObject = \Zend_Json::decode($receivedfrontendObject, \Zend_Json::TYPE_OBJECT);
            foreach($receivedfrontendObject->data[0]->locations AS $frontendLocation) {
                foreach ($frontendLocation->blocks AS $frontendKey => $frontendBlock) {
                    foreach ($this->_page->blocks AS $key => $block) {
                        if ($frontendBlock->id == $block->id) {
                            $this->_page->blocks[$key]->location = $this->_em->getReference('Core\Model\Layout\Location', $frontendLocation->sysname);
                            $this->_page->blocks[$key]->weight = $frontendKey;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $frontendObject = new \Core\Model\Frontend\Simple();
            die($frontendObject->fail('Page object not sent.'));
        }

        $this->_em->flush();

        $frontendObject = new \Core\Model\Frontend\PageInfo();
        echo $frontendObject->success($this->_page);
    }

    /**
     * Gets information on the current page
     */
    public function infoAction()
    {
        if (!\Core\Auth\Auth::getInstance()->getIdentity()->isAllowed($this->_page, 'edit')) {
            throw new \Exception('Not allowed to edit page.');
        }

        $frontendObject = new \Core\Model\Frontend\PageInfo();

        echo $frontendObject->success($this->_page);
    }


    /**
     * Deletes the current page
     *
     * @todo message notifying users if content exists on other pages
     * @todo message notifying users where content exists
     */
    public function deleteAction()
    {
        if (!\Core\Auth\Auth::getInstance()->getIdentity()->isAllowed($this->_page, 'delete')) {
            throw new \Exception('Not allowed to delete page.');
        }

        $this->_pageService->deletePage($this->_page);

        echo new \Core\Model\Frontend\Simple(0, 'Page deleted successfully.');
    }
}