<?php
/**
 * Modo CMS
 */

namespace Core\Form\Element;

/**
 * Radio form element
 *
 * @category   Modo
 * @package    Form
 * @subpackage Element
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Radio.php 102 2010-01-14 22:41:49Z court $
 */
class Radio extends \Zend_Form_Element_Radio
{
    const CSS_CLASS_COLUMN = 'columnlist';
    const CSS_CLASS_LIST   = 'inputlist';
    const CSS_CLASS_RADIO  = 'radio';


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

        $this->setAttrib('class', self::CSS_CLASS_RADIO)
             ->setSeparator("</li>\n<li>");
    }

    /**
     * Is this a multi column list?
     *
     * @return boolean
     */
    public function isMultiColumn()
    {
        $decorator = $this->getDecorator('ulWrapper');
        if ($decorator) {
            $classOption = $decorator->getOption('class');

            if (false !== strpos($classOption, self::CSS_CLASS_COLUMN)) {
                return true;
            }
        }
        
        return false;
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
                 ->addDecorator(array('liWrapper' => 'HtmlTag'),
                                array('tag' => 'li'))
                 ->addDecorator(array('ulWrapper' => 'HtmlTag'),
                                array('tag'   => 'ul',
                                      'class' => self::CSS_CLASS_LIST))
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

    /**
     * Set whether the list should be multi columned
     * 
     * @param  boolean $shouldBeMultiColumn
     * @return Radio *Provides fluid interface*
     */
    public function setMultiColumn($shouldBeMultiColumn = true)
    {
        $decorator = $this->getDecorator('ulWrapper');
        if (!$decorator) {
            return $this;
        }

        $isMultiColumn = $this->isMultiColumn();
        $classOption   = $decorator->getOption('class');
        $cssClass      = self::CSS_CLASS_COLUMN;

        if (!$isMultiColumn && $shouldBeMultiColumn) {
            $classOption .= ' ' . $cssClass;
        } else if ($isMultiColumn) {
            $classOption = trim(str_replace($cssClass, '', $classOption));
        }

        $decorator->setOption('class', $classOption);

        return $this;
    }
}