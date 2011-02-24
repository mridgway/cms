<?php

namespace Core\Form;

/**
 * Form for page model
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class PageWithTemplate extends \Zend_Form
{
    public function init()
    {
        $this->addElements(array(
            Factory\PageElementFactory::getIdElement(),
            Factory\PageElementFactory::getTitleElement(),
            Factory\PageElementFactory::getDescriptionElement()
        ));

        $layout = new \Zend_Form_SubForm();
        $layout->addElement(Factory\PageElementFactory::getLayoutElement()->setName('sysname'));

        $this->addSubForm($layout, 'layout');

        $route = new \Zend_Form_SubForm();
        $route->addElements(array(
            Factory\ContentElementFactory::getIdElement(),
            Factory\RouteElementFactory::getTemplateElement()
        ));

        $pageRoute = new \Zend_Form_SubForm();
        $pageRoute->addSubForm($route, 'route');

        $this->addSubForm($pageRoute, 'pageRoute');

        $submit = new \Core\Form\Element\Submit('submit');
        $submit->setValue('Submit');
        $this->addElement($submit);
    }
}