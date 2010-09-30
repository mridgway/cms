<?php

namespace Core\Form\Element;

/**
 * Textarea form element
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class Wysiwyg extends Textarea
{
    /**
     * {@inheritdoc}
     *
     * Add 'wysywyg' class to textarea.
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        $this->setAttrib('class', 'wysiwyg ckeditor');
    }
}