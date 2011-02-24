<?php

namespace Core\Exception;

class FormException extends \Exception
{
    /**
     * @var Zend_Form
     */
    protected $_form;

    public static function invalidData(\Zend_Form $form)
    {
        $exception = new self('form data is invalid');
        $exception->setForm($form);
        return $exception;
    }

    /**
     * @return Zend_Form
     */
    public function getForm()
    {
        return $this->_form;
    }
    
    public function setForm(\Zend_Form $form)
    {
        $this->_form = $form;
    }
}