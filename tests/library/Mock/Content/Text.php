<?php

namespace Mock\Content;

class Text extends \Core\Model\Content\Text
{
    // set Id for testing puposes
    public function setContentId($id)
    {
        $this->id = $id;
    }
}