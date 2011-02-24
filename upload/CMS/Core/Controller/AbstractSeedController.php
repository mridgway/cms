<?php

namespace Core\Controller;

abstract class AbstractSeedController extends \Zend_Controller_Action
{
    protected $_sc;

    protected $_loremIpsum = null;

    public function init()
    {
        $this->_sc = $this->getInvokeArg('bootstrap')->serviceContainer;

        $user = $this->_sc->getService('auth')->getIdentity();
        if (!in_array($user->getGroup()->getSysname(), array('root'))) {
            throw \Core\Exception\PermissionException::denied();
        }
    }

    public function loremIpsum($wordCount, $format = 'html', $loremIpsum = true)
    {
        if (null === $this->_loremIpsum) {
            require_once('LoremIpsum.class.php');
            $this->_loremIpsum = new \LoremIpsumGenerator();
        }

        return $this->_loremIpsum->getContent($wordCount, $format, $loremIpsum);
    }

    public function setServiceContainer(\sfServiceContainer $sc)
    {
        $this->_sc = $sc;
    }
}