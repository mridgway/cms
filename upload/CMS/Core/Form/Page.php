<?php
/**
 * Modo CMS
 */

namespace Core\Form;

/**
 * Form for Routes
 *
 * @category   Route
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Page.php 297 2010-05-12 13:34:56Z mike $
 */
class Page extends \Core\Form\AbstractPage
{
    public function init()
    {
        parent::init();
        $this->setName('pageForm');

        $pageRoute = new \Modo\Form\Element\Text('pageRoute');
        $pageRoute->setLabel('Url:');
        $pageRoute->setAllowEmpty(true);
        $pageRoute->addValidator(new \Zend_Validate_StringLength(0, 255));
        $pageRoute->setAttrib('disabled', true);
        $this->addElement($pageRoute);

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