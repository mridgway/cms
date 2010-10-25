<?php

namespace Mock\Content;

class Placeholder extends \Core\Model\Content\Placeholder
{
    // set Id for testing puposes
    public function setContentId($id)
    {
        $this->id = $id;
    }
}