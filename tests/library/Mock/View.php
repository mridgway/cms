<?php

namespace Mock;

class View extends \Core\Model\Module\View
{
    public function __construct()
    {
        $this->resource = new \stdClass();
        $this->resource->discriminator = 'text';
        $this->resource->module = new \stdClass();
        $this->resource->module->sysname = 'Core';
        $this->sysname = 'default';
    }
}