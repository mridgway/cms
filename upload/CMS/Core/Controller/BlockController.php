<?php

namespace Core\Controller;

/**
 * Modo CMS
 *
 * Controls the actions that can be done on blocks..
 *
 * @category   Controller
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: BlockController.php 297 2010-05-12 13:34:56Z mike $
 */
class BlockController extends \Zend_Controller_Action
{
    /**
     * @var \Modo\Orm\VersionedEntityManager
     */
    protected $_em;

    /**
     *
     * @var \Core\Model\Block
     */
    protected $_block;

    public function init()
    {
        $this->_em = \Zend_Registry::get('doctrine');

        if (!$blockId = $this->getRequest()->getParam('id', 0)) {
            throw new \Exception('Block not set.');
        }
        $context = $this->getRequest()->getParam('context', 'view');
        $this->_block = $this->_em->getRepository('Core\Model\Block')->find($blockId);
        if (!$this->_block) {
            throw new \Exception('Block does not exist.');
        }
    }

    /**
     * @todo implement this (if necessary)
     */
    public function viewAction()
    {
        if (!$this->_block->canView(\Core\Auth\Auth::getInstance()->getIdentity())) {
            die(new \Core\Model\Frontend\Simple(1, 'Permission denied.'));
        }
        throw new \Exception('Viewing individual blocks not implemented yet.');
    }

    public function editAction()
    {
        if (!$this->_block->canEdit(\Core\Auth\Auth::getInstance()->getIdentity())) {
            die(new \Core\Model\Frontend\Simple(1, 'Permission denied.'));
        }
        
        $frontend = \Core\Service\Manager::get('Block')
                        ->dispatchBlockAction($this->_block, 'editAction', $this->getRequest());

        $frontend->data[0] = array('id' => $this->_block->id);
        echo $frontend;
    }

    /**
     * @todo template cascading for deleting blocks in template... this should be done in
     * placeholder controller
     */
    public function deleteAction()
    {
        if (!$this->_block->canDelete(\Core\Auth\Auth::getInstance()->getIdentity())) {
            die(new \Core\Model\Frontend\Simple(1, 'Permission denied.'));
        }

        $frontend = new \Core\Model\Frontend\Simple();

        $blockService = \Core\Service\Manager::get('Block');

        // dispatch to content controller
        if ($this->_block instanceof \Core\Model\Block\StaticBlock) {
            try {
                $action = 'deleteAction';
                $content = \Core\Service\Manager::get('Block')->getBlockController($this->_block);
            } catch (\Exception $e) {}
        }
        
        \Core\Service\Manager::get('Block')->deleteBlock($this->_block);

        $frontend->data[0] = array('id' => $this->_block->id);
        
        echo $frontend->success();
    }

    /**
     * @todo implement this
     */
    public function configureAction()
    {
        if (!$this->_block->canConfigure(\Core\Auth\Auth::getInstance()->getIdentity())) {
            die(new \Core\Model\Frontend\Simple(1, 'Permission denied.'));
        }

        $form = new \Core\Form\AbstractForm();
        $form->setAction('/direct/block/configure?id=' . $this->_block->getId());
        $form->setMethod('post');
        foreach($this->_block->getConfigProperties() AS $property) {
            if ($property instanceof \Modo\Model\Block\ConfigurableInterface) {
                $form->addElement($property->getConfigurationField());
            }
        }

        if ($this->getRequest()->isPost()) {
            throw new \Exception('Block configuration not implemented yet.');
        } else {
            echo $form->render();
        }
    }
}

