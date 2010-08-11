<?php

namespace Mock\Modo\Orm\Model;

class ModelMock extends \Modo\Orm\Model\AbstractModel
{
    protected $id;
    protected $name;
    protected $num;

    public $hit = false;

    public function setName($name)
    {
        $this->hit = true;
        $this->name = $name;
    }

    public function getName()
    {
        $this->hit = true;
        return $this->name;
    }

    public function testHit()
    {
        $this->hit = true;
    }
}
