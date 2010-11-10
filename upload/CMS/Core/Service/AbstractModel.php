<?php

namespace Core\Service;

/**
 * Base class for services that require the entity manager
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
abstract class AbstractModel extends AbstractService
{
    protected $_className;

    public function _create ($data)
    {
        $formName = $this->getValidationClass();
        $form = new $formName();

        if(!$form->isValid($data)) {
            $exception = ValidationException::invalidData($form->getErrorMessages());
        }

        $class = $this->getClass();
        $object = new $class();
        $objects->fromArray();

        return $object;
    }

    protected function _retrieve($id, $objectName, $propertyArray)
    {
        $this->_objects = $propertyArray;

        $data = array();

        $object = $this->getEntityManager()->getRepository($this->getClass($objectName))->find($id);
        $data[$objectName] = $this->getProperties($object);

        return $data;
    }

    public function getClassName()
    {
        if(\is_null($this->_className)) {
            $this->setClassName($this->getDefaultClassName());
        }
        return $this->_className;
    }

    public function setClassName($className)
    {
        $this->_className = $className;
    }

    protected function _getDefaultClassName()
    {
        $nameArray = explode('\\', \get_class($this));
        $nameArray[1] = 'Model';
        return implode('\\', $nameArray);
    }
}