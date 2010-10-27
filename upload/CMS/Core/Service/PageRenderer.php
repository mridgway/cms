<?php

namespace Core\Service;

/**
 * Service for rendering pages
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class PageRenderer extends \Core\Service\AbstractService
{
    protected $_blockService;

    /**
     * Creates a page from a template replacing any placeholders with the appropriate objects.
     * Placeholders should be string => Core\Model\Content
     *
     * @param \Core\Model\Template
     * @return \Core\Model\Page
     */
    public function renderPage(\Core\Model\AbstractPage $page, $request)
    {
        if(count($page->getBlocks()) > 0)
        {
            // Initialize blocks
            foreach ($page->getBlocks() as $block) {
                $this->getBlockService()->initBlock($block, $request);
            }

            // Render blocks into block wrapper
            foreach ($page->getBlocks() as $block) {
                if ($this->getBlockService()->canView($block)) {
                    $view = $this->getNewView();
                    $view->assign('content', $block->render());
                    $view->assign('block', $block);
                    $view->assign('page', $page);
                    $edit = $request->getParam('edit', true);
                    $view->assign('edit', $edit);
                    $view->setBasePath(APPLICATION_ROOT . '/themes/default/layouts');
                    $block->getLocation()->addContent($view->render('partials/block.phtml'));
                }
            }
        }

        // Set the layout
        $page->getLayout()->assign('page', $page);
        return $page->getLayout()->render();
    }

    public function getBlockService()
    {
        return $this->_blockService;
    }

    public function setBlockService(\Core\Service\Block $service)
    {
        $this->_blockService = $service;
    }

    public function getNewView()
    {
        return new \Zend_View();
    }
}