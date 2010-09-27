<?php

namespace Core\Model\Block\Dynamic\Form;

/**
 * An abstract form block
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
abstract class AbstractForm extends \Core\Model\Block\DynamicBlock
{
    /**
     * Stores the form that is being used on the block
     *
     * @var Zend_Form
     */
    protected $_form;
    
    /**
     * Registers a form as successful by doing a redirect to prevent refresh submits
     */
    public function success($location = null)
    {
        if (null === $location) {
            $location = $this->_request->getRequestUri();
        }
        header("Location:$location");
        die();
    }

    /**
     * Registers a form as failing validation.
     *
     * @return bool
     */
    public function failure($msg = null)
    {
        if ($msg) {
            if (is_array($msg)) {
                $this->_form->addErrors($msg);
            } else {
                $this->_form->addError($msg);
            }
        }
        return false;
    }

    /**
     * Sends the form to be shown on the page
     *
     * @param Zend_Form $form
     */
    public function setForm(\Zend_Form $form)
    {
        $this->_form = $form;
    }

    /**
     * Gets the current form object
     *
     * @return Zend_Form
     */
    public function getForm()
    {
        return $this->_form;
    }

    /**
     * Adds the block id to the form and renders the form
     *
     * @return string
     */
    public function render()
    {
        $blockId = new \Zend_Form_Element_Hidden('block_id');
        $blockId->setValue($this->id);
        $this->_form->addElement($blockId);

        $this->getViewInstance()->assign('form', $this->_form);
        return parent::render();
    }
}