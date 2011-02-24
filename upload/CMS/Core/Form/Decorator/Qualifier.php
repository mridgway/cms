<?php

namespace Core\Form\Decorator;

class Qualifier extends \Zend_Form_Decorator_Abstract
{
    /**
     * Decorate content and/or element
     *
     * @param  string $content
     * @return string
     * @throws Zend_Form_Decorator_Exception when unimplemented
     */
    public function render($content)
    {
        return '<div class="qualifier">' . $content . '<span>' . $this->_options['title'] . '</span></div>';
    }
}