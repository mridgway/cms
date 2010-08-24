<?php

namespace Asset\Form;

/**
 * Form for flash from a URL
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class FlashFromUrl extends \Core\Form\AbstractForm
{
    public function init()
    {
        $url = new \Core\Form\Element\Text('flash_url');
        $url->setLabel('Flash URL:');
        $url->setAllowEmpty(false);
        $this->addElement($url);

        $width = new \Core\Form\Element\Text('flash_width');
        $width->setLabel('Width');
        $width->setAllowEmpty(false);
        $this->addElement($width);

        $height = new \Core\Form\Element\Text('flash_height');
        $height->setLabel('Height');
        $height->setAllowEmpty(false);
        $this->addElement($height);

        $bgcolor = new \Core\Form\Element\Text('flash_background_color');
        $bgcolor->setLabel('Background Color');
        $bgcolor->setAllowEmpty(false);
        $this->addElement($bgcolor);

        $scale = new \Core\Form\Element\Select('flash_scale');
        $scale->setLabel('Scale');
        $scale->addMultiOption(0, 'Show All');
        $scale->addMultiOption(1, 'No Border');
        $scale->addMultiOption(2, 'Exact Fit');
        $scale->addMultiOption(3, 'Do Not Scale');
        $this->addElement($scale);

        $window = new \Core\Form\Element\Select('flash_window');
        $window->setLabel('Windowed Mode');
        $window->addMultiOption(0, 'Window');
        $window->addMultiOption(1, 'Opaque');
        $window->addMultiOption(2, 'Transparent');
        $window->setValue(1);
        $this->addElement($window);

        $variables = new \Core\Form\Element\Textarea('flash_variables');
        $variables->setLabel('Flash Variables:');
        $this->addElement($variables);

        $submit = new \Core\Form\Element\Submit('submit');
        $submit->setLabel('Insert Flash');
        $this->addElement($submit);
    }
}