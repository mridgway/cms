<?php

namespace Core\Model\Block;

/**
 * A block that acts like a controller
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
abstract class DynamicBlock extends \Core\Model\Block
{
    /**
     * The current request object
     *
     * @var Zend_Controller_Request_Http
     */
    protected $_request = null;

    protected $_canUseCache = false;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $_em = null;

    protected $_redirector;

    protected $_parameters = array();

    protected $_cacheTags;

    protected $_output;

    /** @var \Zend_Cache_Core */
    protected $_cache;

    /**
     * Gets called when a block is loaded in the page.
     * All dependencies for block rendering must be set here.
     */
    public function init() {}

    public function run()
    {
        if (!$this->getOutput() && \method_exists($this, '_run')) {
            $this->_run();
            $this->setOutput(parent::render());
            $this->getCache()->save($this->getOutput(), 'block' . \sha1(\serialize($this->getParameters())), $this->getCacheTags());
            $this->getServiceContainer()->getService('blockCacheListener')->reset();
        }
    }

    public function addParameter($key, $value)
    {
        $this->_parameters[$key] = $value;
    }

    public function getParameters()
    {
        $array = array();
        foreach($this->getConfigValues() as $key => $configValue) {
            $array[$key] = $this->getConfigValue($key);
        }

        return \array_merge($array, $this->_parameters, array($this->getView(false)->sysname));
    }

    public function setCacheTags($array)
    {
        $this->_cacheTags = $array;
    }

    public function getCacheTags()
    {
        if(!$this->_cacheTags) {
            $class = \get_class($this);
            $classParts = \explode('\\', $class);
            $this->setCacheTags(array($classParts[0]));
        }
        return $this->_cacheTags;
    }

    public function render()
    {
        if(!$this->getOutput()) {
            $this->setOutput(parent::render());
        }
        return $this->getOutput();
    }

    protected function setOutput($output)
    {
        $this->_output = $output;
    }

    protected function getOutput()
    {
        if(!$this->_output) {
            $this->setOutput($this->getCache()->load('block' . \sha1(\serialize($this->getParameters()))));
        }
        return $this->_output;
    }

    public function setCache(\Zend_Cache_Core $cache)
    {
        $this->_cache = $cache;
    }

    public function getCache()
    {
        if(!$this->_cache) {
            $this->setCache($this->getServiceContainer()->getService('blockCache'));
        }
        return $this->_cache;
    }

    public function getCanUseCache()
    {
        return $this->_canUseCache;
    }

    public function setCanUseCache($bool)
    {
        $this->_canUseCache = $bool;
    }

    /**
     * Gets called if the block's id is submitted in a form
     */
    public function process() {}

    /**
     * Sets the request object
     *
     * @param Zend_Controller_Request_Http $request
     */
    public function setRequest(\Zend_Controller_Request_Http $request)
    {
        $this->_request = $request;
    }

    /**
     * Gets the request object
     *
     * @return Zend_Controller_Request_Http
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * Gets the entity manager
     *
     * @todo remove this
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->getServiceContainer()->getService('doctrine');
    }

    public function canEdit($role)
    {
        return false;
    }

    public function canConfigure($role)
    {
        if (!empty($this->configProperties)) {
            return parent::canConfigure($role);
        }
        return false;
    }

    public function setRedirector($redirector)
    {
        $this->_redirector = $redirector;
    }

    /**
     * @return Zend_Controller_Action_Helper_Redirector
     */
    public function getRedirector()
    {
        if (null == $this->_redirector) {
            $redirector = \Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
            $this->setRedirector($redirector);
        }

        return $this->_redirector;
    }
}