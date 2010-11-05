<?php

namespace Core\Service;

/**
 * Service for block functionality
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Address extends \Core\Service\AbstractService
{
    /**
     * Creates a new Address.  This should not persist or flush because address is a weak entity.
     * (Address should be persisted through cascade operations.)
     * 
     * @param array $data
     * @return \Core\Model\Address
     */
    public function create($data)
    {
        $form = $this->getSubForm();
        $mediator = $this->getMediator($form);

        if(!$mediator->isValid($data)) {
            throw new \Core\Exception\SubFormException($form);
        }

        $address = new \Core\Model\Address();
        $mediator->setInstance($address);
        $mediator->transferValues();

        return $address;
    }

    /**
     * Only need validated SubForm for form errors.
     * 
     * @param array $data
     * @return \Core\Form\SubForm\Address
     */
    public function getValidatedSubForm($data)
    {
        $form = $this->getSubForm();
        $mediator = $this->getMediator($form);
        $mediator->isValid($data);

        return $form;
    }

    public function getSubForm()
    {
        return new \Core\Form\SubForm\Address();
    }

    public function getMediator($form)
    {
        return new \Core\Service\Mediator\Address($form, 'Core\Model\Address');
    }
}