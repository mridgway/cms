<?php

namespace Core\Model\Frontend;

/**
 * Returns information on a page
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class PageInfo extends \Core\Model\Frontend
{

    public $type = 'Page';
    
    public function success(\Core\Model\AbstractPage $page)
    {
        parent::__construct();
        $frontendPage = new \stdClass();
        $frontendPage->id = $page->id;
        $frontendPage->locations = array();

        $frontendPage->actions = $this->_getPageActions($page);
        foreach ($page->layout->locations AS $location) {
            $frontendLocation = new \stdClass();
            $frontendLocation->sysname = $location->sysname;
            $frontendLocation->blocks = array();
            $frontendPage->locations[$location->sysname] = $frontendLocation;
        }
        foreach($page->getBlocks() AS $block) {
            if ($block->canView(\Core\Auth\Auth::getInstance()->getIdentity())) {
                if (isset($frontendPage->locations[$block->location->sysname])) {
                    $frontendBlock = new BlockInfo();
                    $frontendBlock->success($block);
                    $frontendPage->locations[$block->location->sysname]->blocks[] = $frontendBlock->data[0];
                }
            }
        }
        $this->data[] = $frontendPage;

        return $this;
    }

    public function _getPageActions($page)
    {
        $actions = array();

        if ($page->canEdit(\Core\Auth\Auth::getInstance()->getIdentity())) {
            $rearrange = new Action('blockRearrange', '/direct/page/rearrange');
            $actions[$rearrange->name] = $rearrange;
        }

        if ($page->canEdit(\Core\Auth\Auth::getInstance()->getIdentity())) {
            $add = new Action('addBlock', '/direct/page/add-block');
            $add->plugin = 'BlockAdd';
            $actions[$add->name] = $add;
        }

        return $actions;
    }
}