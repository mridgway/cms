<?php

namespace Mock\Service\Validation;

class TestAbstractModel extends \Zend_Form
{
    public function init()
    {
        $nameElement = new \Zend_Form_Element_Text('name');
        $nameElement->addValidator(new \Zend_Validate_StringLength(array('min' => 3, 'max' => 20)));
        $nameElement->isRequired(true);

        $phoneElement = new \Zend_Form_Element_Text('phone');
        $phoneElement->addFilter(new \Zend_Filter_Digits());
        $phoneElement->addValidator(new \Zend_Validate_StringLength(array('min' => 10, 'max' => 11)));
        $phoneElement->isRequired(true);

        $this->addElements(array(
            $nameElement,
            $phoneElement
        ));
    }
}