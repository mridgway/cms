<?php

namespace Core\Controller\Content;

/**
 * Content controller for text (or html) content.
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Controller
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class Text extends \Core\Controller\Content\AbstractController
{
    public function addAction()
    {
        throw new \Exception('Not implemented yet.');
    }

    public function editAction(\Core\Model\Block $block)
    {
        $frontend = new \Core\Model\Frontend\Simple();
        
        $textService = \Core\Service\Manager::get('Core\Service\Text');
        $data = $this->getRequest()->getPost();

        //@var $form \Zend_Form
        $form = new \Zend_Form;
        $form = $textService->getEditForm($block->content, $data);
        $form->setAction('/direct/block/edit/?id='.$block->id);

        if ($this->getRequest()->isPost() && $form->isValid($data)) {
            // update the article
            $form->removeElement('id');
            $block->content->setData($form->getValues());
            $this->getEntityManager()->flush();
            $frontend->html = $block->render();
        } else {
            $frontend->html = $form->render();
        }
        return $frontend;
    }

    public function deleteAction(\Core\Model\Block $block)
    {
        // nothing to do
    }
}