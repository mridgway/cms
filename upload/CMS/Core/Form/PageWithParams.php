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
class PageWithParams extends \Zend_Form
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

        $submit = new \Core\Form\Element\Submit('submit');
        $submit->setValue('Submit');
        $this->addElement($submit);
    }

    public function addRouteSubForm(\Core\Model\PageRoute $pageRoute)
    {
        $params = $pageRoute->getParams();
        $routeTemplate = $pageRoute->getRoute()->getTemplate();

        $pageRoute = new \Zend_Form_SubForm();

        $paramsSubForm = new \Zend_Form_SubForm(array(
            'legend' => "Url: $_SERVER[HTTP_HOST]/$routeTemplate"
        ));
        $paramsSubForm->addElement(Factory\PageElementFactory::getIdElement()->setName('page_route_id'));
        foreach($params as $key => $value) {
            $element = new \Core\Form\Element\Text($key, array(
                'label' => $key,
                'validators' => array(
                    new \Core\Validator\Params()
                )
            ));
            $paramsSubForm->addElement($element);
        }

        $route = new \Zend_Form_SubForm();
        $route->addElements(array(
            Factory\ContentElementFactory::getIdElement()
        ));

        $pageRoute->addSubForm($route, 'route');
        $pageRoute->addSubForm($paramsSubForm, 'params');
        $this->addSubForm($pageRoute, 'pageRoute');

        $this->getElement('id')->setOrder(1);
        $this->getElement('title')->setOrder(3);
        $this->getSubForm('pageRoute')->setOrder(2);
        $this->getElement('description')->setOrder(4);
        $this->getSubForm('layout')->setOrder(5);
        $this->getElement('submit')->setOrder(6);
    }
}