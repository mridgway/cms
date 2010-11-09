<?php

namespace Core\Service;

class MiddleMan
{
    protected $_objects = array();

    public function retrieve($id, $objectName, $propertyArray)
    {
        $this->_objects = $propertyArray;

        $data = array();

        $object = $this->getEntityManager()->getRepository($this->getClass($objectName))->find($id);
        $data[$objectName] = $this->getProperties($object);

        return $data;
    }

    public function getProperties($object)
    {
        $data = array();

        $ref = new \ReflectionClass(get_class($object));
        $methods = $ref->getProperties(\ReflectionProperty::IS_PROTECTED);
        foreach($methods as $value) {
            $getMethod = 'get' . \ucfirst($value->name);
            $property = $object->$getMethod();
            if(!\is_object($property)) {
                $data[$value->name] = $property;
            } elseif(in_array($value->name, $this->_objects)) {
                $data[$value->name] = $this->getProperties($property);
            } elseif($property instanceof \Asset\Model\Asset) {
                $data[$value->name] = $property->getUrl();
            }
        }

        return $data;
    }

    public function create($objectName, $data)
    {
        $formName = $this->getFormClass($objectName);
        $form = new $formName();

        $mediatorName = $this->getMediatorClass($objectName);
        $mediator = new $mediatorName($form, $this->getClass($objectName));

        if(!$mediator->isValid($data)) {
            throw \Core\Exception\SubFormException::invalidData($form);
        }

        $class = $this->getClass($objectName);
        $object = new $class();
        $mediator->setInstance($object);
        $mediator->transferValues();

        foreach($data as $key => $value) {
            if(is_array($value)) {
               $object->{$key} = $this->createFromArray($key, $value);
            }
        }

        return $object;
    }

    public function getClass($objectName)
    {
        switch($objectName) {
            case 'address':
                return 'Core\Model\Address';
            case 'company':
                return 'Pro\Model\Company';
            case 'location':
                return 'Pro\Model\Company\Location';
        }
    }

    public function getFormClass($objectName)
    {
        switch($objectName) {
            case 'address':
                return 'Core\Form\SubForm\Address';
            case 'company':
                return 'Pro\Form\SubForm\Company';
            case 'location':
                return 'Pro\Form\SubForm\Location';
        }
    }

    public function getMediatorClass($objectName)
    {
        switch($objectName) {
            case 'address':
                return 'Core\Service\Mediator\Address';
            case 'company':
                return 'Pro\Service\Mediator\Company';
            case 'location':
                return 'Pro\Service\Mediator\Company\Location';
        }
    }
}