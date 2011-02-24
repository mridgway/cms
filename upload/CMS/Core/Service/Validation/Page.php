<?php

namespace Core\Service\Validation;

/**
 * Form for address model
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Page extends \Zend_Form
{
    public function init()
    {
       $this->addElements(array(
           \Core\Form\Factory\PageElementFactory::getTitleElement(),
            \Core\Form\Factory\PageElementFactory::getDescriptionElement()
        ));
    }
}