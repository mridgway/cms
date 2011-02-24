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

    protected function _create($data)
    {
        $values = $this->_getValidatorValues($data);  

        $class = $this->getClassName();
        $object = new $class();
        $object->fromArray($values);

        return $object;
    }

    protected function _update($data)
    {
        if(!isset($data['id'])) {
            throw new \Exception('Id must be set to update object.');
        }

        $values = $this->_getValidatorValues($data);

        $object = $this->_retrieve($data['id']);
        $object->fromArray($values);

        return $object;
    }
    
    protected function _mergeByKeyRecursive($array1, $array2)
    {
        foreach ($array1 AS $key1 => $val1) {
            if (!array_key_exists($key1, $array2)) {
                continue;
            }

            if (is_array($array1[$key1]) && is_array($array2[$key1])) {
                $array1[$key1] = $this->_mergeByKeyRecursive($array1[$key1], $array2[$key1]);
            } else {
                $array1[$key1] = $array2[$key1];
            }
        }
        return $array1;
    }

    protected function _getValidatorValues($data)
    {
        $class = $this->getValidationClassName();
        $validation = new $class();

        if(!$validation->isValid($data)) {
            throw \Core\Exception\ValidationException::invalidData($this->getClassName(), $validation->getMessages());
        }

        return $this->_mergeByKeyRecursive($data, $validation->getValues());
    }

    protected function _retrieve($id)
    {
        $object = $this->getEntityManager()->getRepository($this->getClassName())->find($id);

        if(null == $object) {
            throw new \Exception('There are no ' . $this->getClassName() . ' objects in storage with id = ' . $id);
        }

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