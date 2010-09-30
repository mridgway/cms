<?php

namespace Core\Filter;

class Url implements \Zend_Filter_Interface
{
    public function filter($value)
    {
        $filteredValue = $value;

        $filteredValue = \preg_replace('/^\/+/', '', $filteredValue);
        $filteredValue = \preg_replace('/\/{2,}/', '/', $filteredValue);

        return $filteredValue;
    }
}