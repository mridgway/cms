<?php

namespace CMS;

abstract class CMSAbstractIntegrationTestCase extends CMSFunctionalTestCase
{
    /**
     * @var \sfServiceContainer
     */
    protected $_sc;

    public function setUp()
    {
        parent::setUp();

        $this->_sc = $this->application->getBootstrap()->serviceContainer;
    }
}