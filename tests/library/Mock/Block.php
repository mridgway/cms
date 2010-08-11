<?php

namespace Mock;

class Block extends \Core\Model\Block
{

    public function __construct()
    {
        parent::__construct(new \Core\Model\View('Core', 'test', 'test'));
    }

    protected $configuration = array(
        'testParam' => array(
            'default' => 'test',
            'required' => true,
            'inheritable' => false,
            'inheritableFrom' => 'Core\Model\Block'
        )
    );

    public function render()
    {
        
    }
}