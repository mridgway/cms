<?php

namespace Mock\Block;

class StaticBlock extends \Core\Model\Block\StaticBlock
{
    // allow id to be set for testing purposes
    public function setBlockId($id)
    {
        $this->id = $id;
    }
}