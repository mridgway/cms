<?php
namespace Taxonomy\Form\Element;

/**
 * Autocomplete form element
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class MultiTag extends \ZendX_JQuery_Form_Element_AutoComplete
{
    public $helper = "multiTag";

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
            $this->addDecorator('UiWidgetElement')
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
     * Make sure the input array only has scalar values.
     *
     * @param array $value
     */
    public function setValue($value)
    {
        if($value) {
            foreach ($value as $key => $content) {
                if (\is_array($content)) {
                    if (\array_key_exists('name', $content)) {
                        $value = array_map(function($c) {return $c['name'];}, $value);
                        break;
                    }
                }
            }
        }

        parent::setValue($value);
    }
}