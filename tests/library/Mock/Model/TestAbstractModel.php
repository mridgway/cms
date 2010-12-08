<?php

namespace Mock\Model;

class TestAbstractModel
{
    public $name;
    public $phone;

    public function fromArray($data)
    {
        $this->name = $data['name'];
        $this->phone = $data['phone'];
    }

    public function toArray($includes = null)
    {
        $data = array();

        $data['name'] = $this->name;
        $data['phone'] = $this->phone;

        return $data;
    }
}