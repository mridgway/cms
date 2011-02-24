<?php

namespace Core\Filter;


/**
 * @package     CMS
 * @subpackage  Core
 * @category    Filter
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class PassHash implements \Zend_Filter_Interface
{
    /**
     * The name of the hashing algorithm
     *
     * @var string
     */
    protected $_algorithm = 'sha256';

    /**
     * The length of the hashing key
     *
     * @var integer
     */
    protected $_keyLength = 6;


    /**
     * Constructor
     *
     * Set the hashing algorithm and/or length trim the key
     *
     * @param string|null  $algorithm
     * @param integer|null $length
     */
    public function __construct($algorithm = null, $length = null)
    {
        if ($algorithm) {
            $this->setAlgorithm($algorithm);
        }

        if ($length) {
            $this->setLength($length);
        }
    }

    /**
     * Implements Zend_Filter_Interface::filter()
     *
     *
     *
     * @param  string $value Password to hash
     * @return string The hash of the password
     */
    public function filter($value)
    {
        return hash_hmac($this->_algorithm,
                         $value,
                         substr($value, 0, $this->_keyLength));
    }

    /**
     * Set the name of the hashing algorithm
     *
     * @param  string $algorithm
     * @return ModoOld_Filter_PassHash *Provides fluid interface*
     */
    public function setAlgorithm($algorithm)
    {
        if (!in_array($algorithm, hash_algos())) {
            throw new \Exception("'$algorithm' is not a valid hashing algorithm");
        }

        $this->_algorithm = $algorithm;

        return $this;
    }

    /**
     * Set the length of the key
     *
     * @param  integer $length
     * @return ModoOld_Filter_PassHash *Provides fluid interface*
     */
    public function setLength($length)
    {
        $this->_keyLength = (int)$length;

        return $this;
    }
}
