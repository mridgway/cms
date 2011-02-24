<?php

namespace Mock\Service;

class TestAbstractModel extends \Core\Service\AbstractModel
{
    public function getDefaultClassName()
    {
        return parent::_getDefaultClassName();
    }

    public function getDefaultValidationClassName()
    {
        return parent::_getDefaultValidationClassName();
    }

    public function create($data)
    {
        return parent::_create($data);
    }

    public function retrieveArray($id, $includes = null)
    {
        return $this->_retrieveArray($id, $includes);
    }

    public function update($data)
    {
        return parent::_update($data);
    }

    public function _getValidatorValues($data)
    {
        return parent::_getValidatorValues($data);
    }
}