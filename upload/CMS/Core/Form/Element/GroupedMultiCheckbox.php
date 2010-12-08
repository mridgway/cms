<?php

namespace Core\Form\Element;

class GroupedMultiCheckbox extends GroupedMulti
{

    public $helper = 'formGroupedMultiCheckbox';

    /**
     * MultiCheckbox is an array of values by default
     * @var bool
     */
    protected $_isArray = true;
    
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
                 ->addDecorator('Label',
                                array('disableFor' => true))
                 ->addDecorator('Errors')
                 ->addDecorator('HtmlTag',
                                array('tag'   => 'div',
                                      'class' => 'element'));
        }
    }
}