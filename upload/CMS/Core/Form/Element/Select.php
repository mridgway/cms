<?php

namespace Core\Form\Element;

/**
 * Select form element
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Select extends \Zend_Form_Element_Select
{
    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('ViewHelper')
                 ->addDecorator('Description',
                                array('placement' => 'prepend',
                                      'tag'       => 'span',
                                      'class'     => 'note'))
                 ->addDecorator('Label')
                 ->addDecorator('Errors')
                 ->addDecorator('HtmlTag',
                                array('tag'   => 'div',
                                      'class' => 'element'));
        }
    }

    /**
     * Converts and array with an 'id' key to a value.
     *
     * @param array|string|integer $value
     * @return integer|string
     */
    public function setValue($value)
    {
        if (is_array($value)) {
            if (\array_key_exists('id', $value)) {
                $value = $value['id'];
            }
        }

        parent::setValue($value);
    }
}