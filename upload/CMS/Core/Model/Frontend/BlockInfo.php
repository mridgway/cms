<?php

namespace Core\Model\Frontend;

/**
 * Returns information on a block
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class BlockInfo extends \Core\Model\Frontend
{

    /**
     * @var \Core\Service\BlockService
     */
    protected $_blockService;

    /**
     * @var \Core\Auth\Auth
     */
    protected $_auth;

    public $type = 'Block';

    public function success(\Core\Model\Block $block)
    {
        parent::__construct();
        $frontendBlock = new \stdClass();
        if ($block->canView($this->getAuth()->getIdentity())) {
            $frontendBlock = new \stdClass();
            $frontendBlock->id = $block->getId();
            $frontendBlock->properties = $this->getBlockService()->getVariables($block);
            $frontendBlock->actions = $this->_getBlockActions($block);
            $frontendBlock->location = $block->getLocation()->getSysname();
            $frontendBlock->weight = $block->getWeight();
            if ($blockType = \Core\Module\Registry::getInstance()->getDatabaseStorage()->getBlockTypeForBlock($block)) {
                $frontendBlock->title = $blockType->getTitle();
            } else {
                $frontendBlock->title = '';
            }
        }
        $this->data[] = $frontendBlock;

        return $this;
    }

    public function _getBlockActions(\Core\Model\Block $block)
    {
        $actions = array();
        if ($block->canMove($this->getAuth()->getIdentity())) {
            $move = new Action('block-move');
            $move->plugin = 'BlockMove';
            $actions[$move->name] = $move;
        }
        if ($block->canEdit($this->getAuth()->getIdentity())) {
            $edit = new Action('block-edit', '/direct/block/edit/?id=' . $block->getId());
            $edit->plugin = 'BlockEdit';
            $actions[$edit->name] = $edit;
        }
        if ($block->canConfigure($this->getAuth()->getIdentity())) {
            $configure = new Action('block-configure', '/direct/block/configure/?id=' . $block->getId());
            $configure->plugin = 'BlockConfigure';
            $actions[$configure->name] = $configure;
        }
        if ($block->canDelete($this->getAuth()->getIdentity())) {
            $delete = new Action('block-delete', '/direct/block/delete/?id=' . $block->getId());
            $delete->plugin = 'BlockDelete';
            $actions[$delete->name] = $delete;
        }
        return $actions;
    }

    public function setBlockService(\Core\Service\Block $blockService)
    {
        $this->_blockService = $blockService;
    }

    protected function getBlockService()
    {
        if(null == $this->_blockService) {
            $this->setBlockService(\Core\Service\Manager::get('Core\Service\Block'));
        }

        return $this->_blockService;
    }

    public function setAuth(\Core\Auth\Auth $auth)
    {
        $this->_auth = $auth;
    }

    protected function getAuth()
    {
        if(null == $this->_auth) {
            $this->setAuth(\Core\Auth\Auth::getInstance());
        }

        return $this->_auth;
    }
}