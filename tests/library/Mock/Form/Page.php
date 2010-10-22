<?php

namespace Mock\Form;

class Page extends \Core\Form\Page
{
    private $_data;
    private $_isValid;

    public function __construct($data, $isValid)
    {
        $this->_data = $data;
        $this->_isValid = $isValid;
    }

    public function getValues($suppressArrayNotation = false)
    {
        return $this->_data;
    }

    public function isValid($data)
    {
        return $this->_isValid;
    }
}