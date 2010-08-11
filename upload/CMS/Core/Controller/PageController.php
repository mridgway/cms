<?php

namespace Core\Controller;

/**
 * Modo CMS
 * 
 * Controls the view action of normal pages.
 *
 * @category   Controller
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: PageController.php 302 2010-05-19 19:22:02Z mike $
 */
class PageController extends \Zend_Controller_Action
{
    /**
     * @var \Modo\Orm\VersionedEntityManager
     */
    protected $_em;

    protected $_page;

    public function init()
    {
        $this->_em = \Zend_Registry::get('doctrine');
        if (!$pageId = $this->getRequest()->getParam('id', false)) {
            throw new \Exception('Page not set.');
        }
        if (!$this->_page = $this->_em->getRepository('Core\Model\AbstractPage')->find($pageId)) {
            throw new \Exception('Page does not exist.');
        }
    }

    public function viewAction()
    {
        if (!\Modo\Auth::getInstance()->getIdentity()->isAllowed($this->_page, 'view')) {
            throw new \Exception('Not allowed to view page.');
        }

        // Initialize blocks
        foreach ($this->_page->getBlocks() as $block) {
            if ($block instanceof \Core\Model\Block\DynamicBlock) {
                // Initialize the dynamic block
                $block->setRequest($this->getRequest());
                $block->setEntityManager($this->_em);
                $block->init();
            }
        }

        // Render blocks into block wrapper
        $blockActions = array();
        foreach ($this->_page->getBlocks() as $block) {
            if ($block->canView(\Modo\Auth::getInstance()->getIdentity())) {
                $view = new \Zend_View();
                $view->assign('content', $block->render());
                $view->assign('block', $block);
                $view->assign('page', $this->_page);
                $edit = $this->getRequest()->getParam('edit', true);
                $view->assign('edit', $edit);
                $view->setBasePath(APPLICATION_ROOT . '/themes/default/layouts');
                $block->getLocation()->addContent($view->render('partials/block.phtml'));
            }
        }

        // Set the layout
        $this->_page->getLayout()->assign('page', $this->_page);
        echo $this->_page->getLayout()->render();
    }

    /**
     * @todo implement this
     */
    public function addBlockAction()
    {
        throw new \Exception('Adding pages not implemented yet.');
    }

    public function editAction()
    {
        $frontend = new \Core\Model\Frontend\Simple();
        
        $form = ($this->_page instanceof \Core\Model\Page) ? new \Core\Form\Page()
                                                          : new \Core\Form\AbstractPage();
        $form->setAction('/direct/page/edit?id=' . $this->_page->getId());

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            if ($form->isValid($data)) {
                unset($data['id']);

                $this->_page->setLayout($this->_em->getReference('Core\Model\Layout', $data['layout']));
                unset($data['layout']);

                $this->_page->setData($data);
                $this->_em->flush();
                $frontend->success();
                header('Location: ' . $this->_page->getUrl());
            } else {
                $frontend->fail();
            }
        }

        $form->setObject($this->_page);
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
        $frontendObject = $this->getRequest()->getParam('page', null);
        if (!isset($frontendObject)) {
            $frontendObject = new \Core\Model\Frontend\Simple();
            die($frontendObject->fail('Page object not sent.'));
        }

        $frontendObject = \Zend_Json::decode($frontendObject, \Zend_Json::TYPE_OBJECT);
        foreach($frontendObject->data[0]->locations AS $frontendLocation) {
            foreach ($frontendLocation->blocks AS $frontendKey => $frontendBlock) {
                foreach ($this->_page->blocks AS $key => $block) {
                    if ($frontendBlock->id == $block->id) {
                        $this->_page->blocks[$key]->location = $this->_em->getReference('Core\Model\Layout\Location', $frontendLocation->sysname);
                        $this->_page->blocks[$key]->weight = $frontendKey;
                    }
                }
            }
        }
        
        $this->_em->flush();

        $frontendObject = new \Core\Model\Frontend\PageInfo();
        echo $frontendObject->success($this->_page);
    }

    /**
     * Gets information on the current page
     */
    public function getInfoAction()
    {
        $frontendObject = new \Core\Model\Frontend\PageInfo();

        echo $frontendObject->success($this->_page);
    }


    /**
     * @todo implement this
     */
    public function deleteAction()
    {
        throw new \Exception('Deleting pages not implemented yet.');
    }
}