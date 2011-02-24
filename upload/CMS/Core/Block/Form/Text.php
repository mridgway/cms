<?php

namespace Core\Block\Form;

/**
 * Block for editing shared text
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Block
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity
 */
class Text extends \Core\Model\Block\Dynamic\Form
{
    public function init()
    {
        $user = $this->getServiceContainer()->getService('auth')->getIdentity();
        if (!in_array($user->getGroup()->getSysname(), array('admin', 'root'))) {
            throw \Core\Exception\PermissionException::denied();
        }

        $id = $this->getRequest()->getParam('id', false);
        if($id) {
            $text = $this->getServiceContainer()->getService('textService')->retrieve($id);
        }

        $form = $this->getForm();
        $form->populate($text->toArray());

        if($this->getFlashMessenger()->getMessages()) {
            $this->getView()->assign('messages', $this->getFlashMessenger()->getMessages());
        }
    }

    public function process()
    {
        $user = $this->getServiceContainer()->getService('auth')->getIdentity();
        if (!in_array($user->getGroup()->getSysname(), array('admin', 'root'))) {
            throw \Core\Exception\PermissionException::denied();
        }
        
        $data = $this->getRequest()->getPost();

        $form = $this->getForm();
        $form->populate($data);

        if($form->isValid($data)) {
            $data = $form->getValues();
            try {
                $textService = $this->getServiceContainer()->getService('textService');
                $text = $textService->retrieve($data['id']);
                $textService->update($text, $data['title'], $data['content']);
                $this->getFlashMessenger()->addMessage('success');
                return $this->success();
            } catch (\Exception $e) {
                die($e);
            }
        }

        return $this->failure();
    }


    public function configure()
    {
    }

    public function getForm()
    {
        if(null == parent::getForm()) {
            $form = new \Core\Form\SharedText();
            $this->setForm($form);
        }

        return parent::getForm();
    }
}