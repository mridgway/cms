<?php

namespace Asset\Form\Element;

/**
 * Asset form element
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Asset extends \Zend_Form_Element_Text
{
    public $group;

    public function init()
    {
        parent::init();
        $this->setIgnore(false);
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
            $this->addDecorator('ViewHelper',
                                array(
                                    'helper' => 'FormUpload'
                                ))
                 ->addDecorator('Label')
                 ->addDecorator('HtmlTag', array(
                     'class' => 'element'
                 ))
                 ->addDecorator('Description',
                                array('placement' => 'prepend',
                                      'tag'       => 'div',
                                      'class'     => 'note'));
        }
    }

    /**
     * @param string group
     * @return Asset
     */
    public function setGroup($group = 'tmp')
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }
}