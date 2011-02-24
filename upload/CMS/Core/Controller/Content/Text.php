<?php

namespace Core\Controller\Content;

/**
 * Content controller for text (or html) content.
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Controller
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Text extends \Core\Controller\Content\AbstractController
{
    public function addAction(\Core\Model\Block $block = null)
    {
        $frontend = new \Core\Model\Frontend\Simple();

        $textService = $this->getServiceContainer()->getService('textService');
        $data = $this->getRequest()->getPost();

        $isShared = $this->getRequest()->getParam('isShared', false);
        if($isShared) {
            $form = new \Core\Form\SharedText();
        } else {
            $form = $textService->getAddForm();
        }

        $blockView = new \Core\Model\View('Core', 'Block/addStandard');
        $blockView->assign('form', $form);

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($data)) {
                // update the article
                $form->removeElement('id');
                $text = $this->_sc->getService('textService')->create($data['title'], $data['content']);
                $frontend->html = $block ? $block->render() : $blockView->render();
                $frontend->data = new \stdClass();
                $frontend->data->url = '/index/text';
                $frontend->data->id = $text->getId();
            } else {
                $frontend->html = $blockView->render();
                $frontend->fail();
            }
        } else {
            $frontend->html = $blockView->render();
        }
        return $frontend;
    }

    public function editAction(\Core\Model\Block $block = null)
    {
        $frontend = new \Core\Model\Frontend\Simple();
        
        $textService = $this->getServiceContainer()->getService('textService');
        $data = $this->getRequest()->getPost();

        //@var $form \Zend_Form
        $form = $textService->getEditForm($block->content, $data);
        $form->setAction('/direct/block/edit/?id='.$block->id);

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($data)) {
                // update the article
                $form->removeElement('id');
                $data = $form->getValues();
                $textService->update($block->content, $data['title'], $data['content']);
                $frontend->html = $block->render();
            } else {
                $view = new \Core\Model\View('Core', 'Block/Form/default');
                $view->assign('form', $form);
                $frontend->html = $view->render();
                $frontend->fail();
            }
        } else {
            $view = new \Core\Model\View('Core', 'Block/Form/default');
            $view->assign('form', $form);
            $frontend->html = $view->render();
        }
        return $frontend;
    }

    public function deleteAction(\Core\Model\Block $block)
    {
        // nothing to do
    }
}