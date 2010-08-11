<?php
/**
 * Modo CMS
 */

namespace Asset\Form;

/**
 * Form for flash from a URL
 *
 * @category   Form
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: FlashFromUrl.php 297 2010-05-12 13:34:56Z mike $
 */
class FlashFromUrl extends \Modo\Form\AbstractForm
{
    public function init()
    {
        $url = new \Modo\Form\Element\Text('flash_url');
        $url->setLabel('Flash URL:');
        $url->setAllowEmpty(false);
        $this->addElement($url);

        $width = new \Modo\Form\Element\Text('flash_width');
        $width->setLabel('Width');
        $width->setAllowEmpty(false);
        $this->addElement($width);

        $height = new \Modo\Form\Element\Text('flash_height');
        $height->setLabel('Height');
        $height->setAllowEmpty(false);
        $this->addElement($height);

        $bgcolor = new \Modo\Form\Element\Text('flash_background_color');
        $bgcolor->setLabel('Background Color');
        $bgcolor->setAllowEmpty(false);
        $this->addElement($bgcolor);

        $scale = new \Modo\Form\Element\Select('flash_scale');
        $scale->setLabel('Scale');
        $scale->addMultiOption(0, 'Show All');
        $scale->addMultiOption(1, 'No Border');
        $scale->addMultiOption(2, 'Exact Fit');
        $scale->addMultiOption(3, 'Do Not Scale');
        $this->addElement($scale);

        $window = new \Modo\Form\Element\Select('flash_window');
        $window->setLabel('Windowed Mode');
        $window->addMultiOption(0, 'Window');
        $window->addMultiOption(1, 'Opaque');
        $window->addMultiOption(2, 'Transparent');
        $window->setValue(1);
        $this->addElement($window);

        $variables = new \Modo\Form\Element\Textarea('flash_variables');
        $variables->setLabel('Flash Variables:');
        $this->addElement($variables);

        $submit = new \Modo\Form\Element\Submit('submit');
        $submit->setLabel('Insert Flash');
        $this->addElement($submit);
    }
}