<?php

namespace Core\Controller;

/**
 * Controller for actions on blocks
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Controller
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class BlockController extends \Zend_Controller_Action
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $_em;

    /**
     * @var sfServiceContainer
     */
    protected $_sc;

    /**
     *
     * @var \Core\Model\Block
     */
    protected $_block;

    public function init()
    {
        $this->_sc = $this->getInvokeArg('bootstrap')->serviceContainer;
        $this->_em = \Zend_Registry::get('doctrine');
    }

    public function viewAction()
    {
        if (!$blockId = $this->getRequest()->getParam('id', 0)) {
            throw new \Exception('Block not set.');
        }
        $context = $this->getRequest()->getParam('context', 'view');
        $this->_block = $this->_em->getRepository('Core\Model\Block')->find($blockId);
        if (!$this->_block) {
            throw new \Exception('Block does not exist.');
        }

        if (!$this->_block->canView(\Core\Auth\Auth::getInstance()->getIdentity())) {
            die(new \Core\Model\Frontend\Simple(1, 'Permission denied.'));
        }

        $this->_sc->getService('BlockService')->initBlock($this->_block, $this->getRequest());
        echo $this->_block->render();
        $view = $this->_block->getView();
        \ZendX_JQuery::enableView($view);
        echo $view->jQuery()
            ->setRenderMode(\ZendX_JQuery::RENDER_JAVASCRIPT | \ZendX_JQuery::RENDER_JQUERY_ON_LOAD);
    }

    public function viewTypeAction()
    {
        if ($blockType = $this->getRequest()->getParam('type', false)) {
            /* @var $blockType Core\Model\Module\BlockType */
            $blockType = $this->_em->getRepository('Core\Model\Module\BlockType')
                    ->findOneByDiscriminator($blockType);
            if (!$blockType) {
                throw new \Exception('Block type does not exist.');
            }

            $block = $blockType->createInstance(array(
                $blockType->createView('default')
            ));

            $blockService = $this->_sc->getService('BlockService');
            $blockService->initBlock($block, $this->getRequest());
            echo $block->render();
            $view = $block->getView();
            \ZendX_JQuery::enableView($view);
            echo $view->jQuery()
                ->setRenderMode(\ZendX_JQuery::RENDER_JAVASCRIPT | \ZendX_JQuery::RENDER_JQUERY_ON_LOAD);
        }
    }

    public function editAction()
    {
        if (!$blockId = $this->getRequest()->getParam('id', 0)) {
            throw new \Exception('Block not set.');
        }
        $context = $this->getRequest()->getParam('context', 'view');
        $this->_block = $this->_em->getRepository('Core\Model\Block')->find($blockId);
        if (!$this->_block) {
            throw new \Exception('Block does not exist.');
        }
        
        if (!$this->_block->canEdit(\Core\Auth\Auth::getInstance()->getIdentity())) {
            die(new \Core\Model\Frontend\Simple(1, 'Permission denied.'));
        }
        
        $frontend = $this->_sc->getService('blockService')
                        ->dispatchBlockAction($this->_block, 'editAction', $this->getRequest());

        $frontend->data[0] = array('id' => $this->_block->id);

        // Attach jquery scripts
        $view = new \Zend_View();
        \ZendX_JQuery::enableView($view);
        $frontend->html .= (string) $view->jQuery()
            ->setRenderMode(\ZendX_JQuery::RENDER_JAVASCRIPT | \ZendX_JQuery::RENDER_JQUERY_ON_LOAD);

        echo $frontend;
    }

    /**
     * @todo template cascading for deleting blocks in template... this should be done in
     * placeholder controller
     */
    public function deleteAction()
    {
        if (!$blockId = $this->getRequest()->getParam('id', 0)) {
            throw new \Exception('Block not set.');
        }
        $context = $this->getRequest()->getParam('context', 'view');
        $this->_block = $this->_em->getRepository('Core\Model\Block')->find($blockId);
        if (!$this->_block) {
            throw new \Exception('Block does not exist.');
        }
        
        if (!$this->_block->canDelete(\Core\Auth\Auth::getInstance()->getIdentity())) {
            die(new \Core\Model\Frontend\Simple(1, 'Permission denied.'));
        }

        $frontend = new \Core\Model\Frontend\Simple();

        $blockService = $this->_sc->getService('blockService');

        // dispatch to content controller
        if ($this->_block instanceof \Core\Model\Block\StaticBlock) {
            try {
                $blockService->dispatchBlockAction($this->_block, 'deleteAction', $this->getRequest());
            } catch (\Exception $e) {}
        }
        
        $blockService->delete($this->_block);

        $frontend->data[0] = array('id' => $this->_block->id);
        
        echo $frontend->success();
    }

    /**
     * @todo implement this
     */
    public function configureAction()
    {
        if (!$blockId = $this->getRequest()->getParam('id', 0)) {
            throw new \Exception('Block not set.');
        }
        $context = $this->getRequest()->getParam('context', 'view');
        $this->_block = $this->_em->getRepository('Core\Model\Block')->find($blockId);
        if (!$this->_block) {
            throw new \Exception('Block does not exist.');
        }
        
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

