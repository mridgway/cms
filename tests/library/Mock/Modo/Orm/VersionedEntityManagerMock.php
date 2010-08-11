<?php

namespace Mock\Modo\Orm;

class VersionedEntityManagerMock extends \Modo\Orm\VersionedEntityManager
{
    public $wasCalled = false;

    public function __construct()
    {
    }

    public function flush ()
    {
        $this->wasCalled = true;
        parent::flush();
    }
}