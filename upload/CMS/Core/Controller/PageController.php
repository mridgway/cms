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
     * @var \Core\Model\Page
     */
    protected $_page;

    /**
     * @var \sfServiceContainer
     */
    protected $_sc;

    /**
     * @var \Core\Service\PageService
     */
    protected $_pageService;

    public function init()
    {
        $this->setServiceContainer($this->getInvokeArg('bootstrap')->serviceContainer);

         if ($this->getRequest()->getParam('current_page_id') == false) {
            $pageId = $this->getRequest()->getParam('id', false);
            $this->getRequest()->setParam('current_page_id', $pageId);
        }
    }

    public function viewAction()
    {
        $page = $this->getPageService()->getPageIfAllowed($this->getRequest()->getParam('current_page_id', false), 'view');
        if ($pageNumber = $this->getRequest()->getParam('page')) {
            $page->getLayout()->assign('paginationPage', $pageNumber);
        }
        $pageRenderer = $this->_sc->getService('pageRendererService');
        $content = $pageRenderer->renderPage($page, $this->getRequest());
        $this->getResponse()->setBody($content);
    }

    public function addBlockAction()
    {
        $user = $this->_sc->getService('auth')->getIdentity();
        if (!in_array($user->getGroup()->getSysname(), array('pro', 'admin', 'root'))) {
            throw \Core\Exception\PermissionException::denied();
        }

        $page = $this->getPageService()->getPageIfAllowed($this->getRequest()->getParam('current_page_id', false), 'edit');
        $location = $this->_sc->getService('locationService')->getLocation($this->getRequest()->getParam('location', false));
        $type = $this->getRequest()->getParam('type');

        switch($type) {
            case 'standard':
                $controller = new \Core\Controller\Content\Text();
                $controller->setServiceContainer($this->_sc);
                $controller->setRequest($this->getRequest());
                $controller->setResponse($this->getResponse());

                $frontend = $controller->addAction();

                $block = null;
                if ($this->getRequest()->isPost() && $frontend->code->id <= 0) {
                    $textId = $frontend->data->id;
                    $text = $this->_sc->getService('doctrine')->getReference('Core\Model\Content\Text', $textId);
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
        $this->getPageService()->isAllowed('AllPages', 'add');

        $form = $this->getPageService()->getForm();
        $form->setAction('/direct/page/add');
        $frontend = new \Core\Model\Frontend\Simple();

        if($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            if($form->isValid($data)) {
                try {
                    $page = $this->getPageService()->create($data);
                    $frontend->data['url'] = $page->getURL();
                    $frontend->success();
                } catch (\Core\Exception\ValidationException $e) {
                    $frontend->html = (string) $e;
                    $frontend->fail();
                }
            } else {
                $frontend->html = $form->render();
                $frontend->fail();
            }
        } else {
            $frontend->html = $form->render();
        }

        $html = $this->getRequest()->getParam('html');
        if (isset($html)) {
            $page = $this->getPageService()->getPage($this->getRequest()->getParam('id', false));
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
        $page = $this->getPageService()->getPageIfAllowed($this->getRequest()->getParam('current_page_id', false), 'edit');

        $data = $this->getPageService()->retrieveAsArray($page->getId());

        $form = $this->getPageService()->getForm($page->getId());
        $form->setAction('/direct/page/edit?id=' . $page->getId());
        $frontend = new \Core\Model\Frontend\Simple();
        $form->populate($data);

        if($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            if($form->isValid($data)) {
                try {
                    $page = $this->getPageService()->updatePage($data);
                    $frontend->data['url'] = $page->getURL();
                    $frontend->success();
                } catch (\Core\Exception\ValidationException $e) {
                    $frontend->html = (string) $e;
                    $frontend->fail();
                }
            } else {
                $frontend->html = $form->render();
                $frontend->fail();
            }
        } else {
            $frontend->html = $form->render();
        }

        echo $frontend;
    }

    /**
     * Rearranges the blocks on the page
     */
    public function rearrangeAction()
    {
        $page = $this->getPageService()->getPageIfAllowed($this->getRequest()->getParam('current_page_id', false), 'edit');

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

            $this->getPageService()->update($page, $pageObject);
        } catch (\Exception $e) {
            $frontendObject = new \Core\Model\Frontend\Simple();
            die($frontendObject->fail('Could not rearrange blocks: ' . $e->getMessage()));
        }

        $frontendObject = new \Core\Model\Frontend\PageInfo();
        echo $frontendObject->success($page);
    }

    /**
     * Gets information on the current page
     */
    public function infoAction()
    {
        $user = $this->getServiceContainer()->getService('auth')->getIdentity();
        if (!in_array($user->getGroup()->getSysname(), array('admin', 'root'))) {
            throw \Core\Exception\PermissionException::denied();
        }

        $page = $this->getPageService()->getPageIfAllowed($this->getRequest()->getParam('current_page_id', false), 'edit');

        $frontendObject = new \Core\Model\Frontend\PageInfo();

        echo $frontendObject->success($page);
    }

    public function sitemapAction()
    {
        $pages = $this->_sc->getService('doctrine')->getRepository('Core\Model\Page')->getAccessiblePages();

        $this->getResponse()->setHeader('Content-type', 'application/xml', true);
        $this->getResponse()->setHeader('charset', 'utf-8', true);
        echo \Core\Model\View::renderScript('Core', 'sitemap.xml', array(
            'pages' => $pages
        ));
    }

    public function indexAction()
    {
        $pages = $this->_sc->getService('doctrine')->getRepository('Core\Model\Page')->getAccessiblePages();

        $this->getResponse()->setHeader('charset', 'utf-8', true);
        foreach ($pages AS $page) {
            echo $page->getUrl() . PHP_EOL;
        }
    }


    /**
     * Deletes the current page
     *
     * @todo message notifying users if content exists on other pages
     * @todo message notifying users where content exists
     */
    public function deleteAction()
    {
        $page = $this->getPageService()->getPageIfAllowed($this->getRequest()->getParam('current_page_id', false), 'delete');

        $blockService = $this->_sc->getService('blockService');
        foreach ($page->getBlocks() AS $block) {
            if ($block instanceof \Core\Model\Block\StaticBlock) {
                try {
                    $blockService->dispatchBlockAction($block, 'deleteAction', $this->getRequest());
                } catch (\Exception $e) {}
            }
        }

        $this->getPageService()->deletePage($page);

        echo new \Core\Model\Frontend\Simple(0, 'Page deleted successfully.');
    }

    public function setServiceContainer(\sfServiceContainer $sc)
    {
        $this->_sc = $sc;
    }

    public function getServiceContainer()
    {
        return $this->_sc;
    }

    public function setPageService(\Core\Service\Page $pageService)
    {
        $this->_pageService = $pageService;
    }

    public function getPageService()
    {
        if(!$this->_pageService) {
            $this->setPageService($this->getServiceContainer()->getService('pageService'));
        }
        return $this->_pageService;
    }
}