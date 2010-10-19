<?php

namespace Core\Auth;

/**
 * Wrapper for hashing library
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Auth
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Hasher
{
    /**
     * @var PasswordHasher
     */
    protected $hasher;


    public function __construct($iterationCount = 10, $bc = false)
    {
        require_once('phpass/PasswordHash.php');
        $this->hasher = new \PasswordHash($iterationCount, $bc);
    }

    /**
     *
     * @param string $string Original string
     * @return string Hashed string
     */
    public function hash($string)
    {
        return $this->hasher->HashPassword($string);
    }

    /**
     *
     * @param string $string
     * @param string $hashedString
     * @return boolean
     */
    public function checkHash($string, $hashedString)
    {
        return $this->hasher->CheckPassword($string, $hashedString);
    }
}