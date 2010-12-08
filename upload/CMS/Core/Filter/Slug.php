<?php

namespace Core\Filter;

/**
 * Slug filter.
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Filter
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Slug implements \Zend_Filter_Interface
{
    /**
    * Creates a URL friendly slug (NOT UNIQUE)
    *
    * @param string $str
    * @return string
    */
    public function filter($str)
    {
       $str = strtolower(trim($str));
       $str = preg_replace('/[^a-z0-9-]/', '-', $str);
       $str = preg_replace('/-+/', "-", $str);
       $str = \preg_replace('/-$/', '', $str);
       return $str;
    }
}