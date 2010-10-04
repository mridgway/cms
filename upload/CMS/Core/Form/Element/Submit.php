<?php

namespace Core\Form\Element;

/**
 * Submit form element
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Submit extends \Zend_Form_Element_Submit
{
    /**
     * {@inheritdoc}
     *
     * Add default element attributes.
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        $this->setAttrib('class', 'button');
    }
    
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
                                      'tag'       => 'div',
                                      'class'     => 'note'));
        }
    }
}