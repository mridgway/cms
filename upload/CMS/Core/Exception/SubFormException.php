<?php

namespace Core\Exception;

class SubFormException extends \Exception
{
    /**
     * @var array
     */
    protected $_subForms = array();

    public static function invalidData(\Zend_Form_SubForm $form)
    {
        $exception = new self('subform data is invalid');
        $exception->add($form);
        return $exception;
    }

    public function getSubForms()
    {
        return $this->_subForms;
    }

    public function addSubForm(\Zend_Form_SubForm $form)
    {
        $this->_subForms[] = $form;
    }
}