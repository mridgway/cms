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
        
        $this->addElement(Page\PageElementFactory::getPageRouteElement());

        $this->getElement('title')->setOrder(0);
        $this->getElement('pageRoute')->setOrder(1);
        $this->getElement('description')->setOrder(2);
        $this->getElement('layout')->setOrder(3);
        $this->getElement('submit')->setOrder(4);
    }

    /**
     * {@inheritdoc}
     *
     * @param object $object
     */
    public function setObject($object)
    {
        parent::setObject($object);
        $pageRoute = $object->getPrimaryPageRoute();
        $template = (null === $pageRoute)? '' : '/' . $pageRoute->getRoute()->getTemplate();
        $this->getElement('pageRoute')->setValue($template);
    }
}