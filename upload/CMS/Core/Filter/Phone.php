<?php

/**
 * @category   Modo
 * @package    ModoOld_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Phone.php 127 2010-02-24 19:05:05Z court $
 */
class Core_View_Helper_Phone extends \Zend_View_Helper_Abstract
{
    /**
     * The format of the extension telephone string
     *
     * @var string
     */
    protected $_extFormat = " ext %s";

    /**
     * The format of the main telephone string
     *
     * @var string
     */
    protected $_format = "%s-%s-%s";


    /**
     * The length of the unparsed phone number
     *
     * @var integer
     */
    private $_length = 0;

    /**
     * The unparsed phone number
     *
     * @var string|null
     */
    private $_unparsed = '';


    public function __toString()
    {
        $str = 'N/A';

        if ($this->getUnparsedLength() >= 10) {
            $str = sprintf($this->_format, $this->getAreaCode(),
                                           $this->getExchangeCode(),
                                           $this->getStationCode());

            if ($ext = $this->getExtension()) {
                $str .= sprintf($this->_extFormat, $ext);
            }

            $str = $this->view->escape($str);
        }

        return $str;
    }

    /**
     * Helper accessor
     *
     * Format an unparsed phone number
     *
     * @param  string|null $unparsed
     * @return ModoOld_View_Helper_Phone *Provides fluid interface*
     */
    public function phone($unparsed = null)
    {
        if ($unparsed) {
            $this->setUnparsedNumber($unparsed);
        }

        return $this;
    }

    /**
     * Get the area code from the unparsed phone number
     *
     * @return string
     */
    public function getAreaCode()
    {
        $str = '';

        if ($this->getUnparsedLength() >= 10) {
            $str = substr($this->getUnparsedNumber(), 0, 3);
        }

        return $str;
    }

    /**
     * Get the exchange code from the unparsed phone number
     *
     * @return string
     */
    public function getExchangeCode()
    {
        $str = '';

        if ($this->getUnparsedLength() >= 10) {
            $str = substr($this->getUnparsedNumber(), 3, 3);
        }

        return $str;
    }

    /**
     * Get the station code from the unparsed phone number
     *
     * @return string
     */
    public function getStationCode()
    {
        $str = '';

        if ($this->getUnparsedLength() >= 10) {
            $str = substr($this->getUnparsedNumber(), 6, 4);
        }

        return $str;
    }

    /**
     * Get the extension from the unparsed phone number
     *
     * @return string
     */
    public function getExtension()
    {
        $str = '';

        if ($this->getUnparsedLength() > 10) {
            $str = substr($this->getUnparsedNumber(), 10);
        }

        return $str;
    }

    /**
     * Get the length of the unparsed phone number
     *
     * @return integer
     */
    public function getUnparsedLength()
    {
        return $this->_length;
    }

    /**
     * Get the unparsed phone number
     *
     * @return string
     */
    public function getUnparsedNumber()
    {
        return $this->_unparsed;
    }

    /**
     * Set the unparsed phone number
     *
     * @param  string $unparsed
     * @return ModoOld_View_Helper_Phone *Provides fluid interface*
     */
    public function setUnparsedNumber($unparsed)
    {
        $unparsed = (string)$unparsed;

        $this->_length   = strlen($unparsed);
        $this->_unparsed = $unparsed;

        return $this;
    }
}