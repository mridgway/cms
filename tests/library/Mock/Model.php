<?php

namespace Mock;

class Model extends \Core\Model\AbstractModel
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
