<?php
/**
 * Modo CMS
 */

namespace Asset\Form;

/**
 * Form for Assets from a URL
 *
 * @category   Form
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: ImageFromUrl.php 297 2010-05-12 13:34:56Z mike $
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