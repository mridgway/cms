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

    public $type = 'Block';

    public function success(\Core\Model\Block $block)
    {
        parent::__construct();
        $frontendBlock = new \stdClass();
        if ($block->canView(\Core\Auth\Auth::getInstance()->getIdentity())) {
            $frontendBlock = new \stdClass();
            $frontendBlock->id = $block->id;
            $frontendBlock->properties = \Core\Service\Manager::get('Core\Service\Block')->getVariables($block);
            $frontendBlock->actions = $this->_getBlockActions($block);
        }
        $this->data[] = $frontendBlock;

        return $this;
    }

    public function _getBlockActions(\Core\Model\Block $block)
    {
        $actions = array();
        if ($block->canMove(\Core\Auth\Auth::getInstance()->getIdentity())) {
            $move = new Action('block-move');
            $move->plugin = 'BlockMove';
            $actions[$move->name] = $move;
        }
        if ($block->canEdit(\Core\Auth\Auth::getInstance()->getIdentity())) {
            $edit = new Action('block-edit', '/direct/block/edit/?id=' . $block->id);
            $edit->plugin = 'BlockEdit';
            $actions[$edit->name] = $edit;
        }
        if ($block->canConfigure(\Core\Auth\Auth::getInstance()->getIdentity())) {
            $configure = new Action('block-configure', '/direct/block/configure/?id=' . $block->id);
            $configure->plugin = 'BlockConfigure';
            $actions[$configure->name] = $configure;
        }
        if ($block->canDelete(\Core\Auth\Auth::getInstance()->getIdentity())) {
            $delete = new Action('block-delete', '/direct/block/delete/?id=' . $block->id);
            $delete->plugin = 'BlockDelete';
            $actions[$delete->name] = $delete;
        }
        return $actions;
    }
}