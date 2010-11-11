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
    protected $_validationClassName;

    public function _create ($data)
    {
        $class = $this->getValidationClassName();
        $validation = new $class();

        if(!$validation->isValid($data)) {
            throw \Core\Exception\ValidationException::invalidData($validation->getErrorMessages());
        }

        $class = $this->getClassName();
        $object = new $class();
        $object->fromArray($validation->getValues());

        return $object;
    }

    protected function _retrieve($id)
    {
        $object = $this->getEntityManager()->getRepository($this->getClassName())->find($id);

        return $object;
    }

    protected function _retrieveArray($id, $includes = null)
    {
        $object = $this->_retrieve($id);

        return $object->toArray($includes);
    }

    public function getClassName()
    {
        if(\is_null($this->_className)) {
            $this->setClassName($this->_getDefaultClassName());
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

    public function getValidationClassName()
    {
        if(\is_null($this->_validationClassName)) {
            $this->setValidationClassName($this->_getDefaultValidationClassName());
        }
        return $this->_validationClassName;
    }

    public function setValidationClassName($className)
    {
        $this->_validationClassName = $className;
    }

    protected function _getDefaultValidationClassName()
    {
        $nameArray = explode('\\', \get_class($this));
        \array_splice($nameArray, 2, 0, 'Validation');
        return implode('\\', $nameArray);
    }
}