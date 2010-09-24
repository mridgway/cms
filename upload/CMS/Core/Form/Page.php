<?php

namespace Core\Form;

/**
 * Form for page model
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class Page extends \Core\Form\AbstractPage
{
    public function init()
    {
        parent::init();
        $this->setName('pageForm');
        
        $this->addElement(Factory\PageElementFactory::getPageRouteElement());

        $this->getElement('id')->setOrder(0);
        $this->getElement('title')->setOrder(1);
        $this->getElement('pageRoute')->setOrder(2);
        $this->getElement('description')->setOrder(3);
        $this->getElement('layout')->setOrder(4);
        $this->getElement('submit')->setOrder(5);
    }

    /**
     * {@inheritdoc}
     *
     * @param object $object
     */
    public function setObject($object)
    {
        parent::setObject($object);
        $pageRoute = $object->getPageRoute();
        $template = (null === $pageRoute)? '' : '/' . $pageRoute->getRoute()->getTemplate();
        $this->getElement('pageRoute')->setValue($template);
    }
}