<?php

namespace Asset\Form;

/**
 * Form for assets from a URL
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class ImageFromUrl extends \Core\Form\AbstractForm
{
    public function init()
    {
        $url = new \Core\Form\Element\Text('image_url');
        $url->setLabel('Image URL:');
        $url->setAllowEmpty(false);
        $this->addElement($url);

        $caption = new \Core\Form\Element\Textarea('image_caption');
        $caption->setLabel('Caption:');
        $this->addElement($caption);

        $submit = new \Core\Form\Element\Submit('submit');
        $submit->setLabel('Insert Image');
        $this->addElement($submit);
    }
}