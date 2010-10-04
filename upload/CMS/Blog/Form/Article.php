<?php

namespace Blog\Form;

/**
 * Form for blog articles
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Article extends \Core\Form\AbstractForm
{
    public function init()
    {
        $this->addElements(array(
            Factory\ArticleElementFactory::getIdElement(),
            Factory\ArticleElementFactory::getTitleElement(),
            Factory\ArticleElementFactory::getContentElement()
        ));

        $submit = new \Core\Form\Element\Submit('submit');
        $submit->setValue('Submit');
        $this->addElement($submit);
    }
}