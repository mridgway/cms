<?php

namespace Core\Filter;

/**
 * Website filter.
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Filter
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class TwitterUsername implements \Zend_Filter_Interface
{
    /**
     * {@inheritdoc}
     *
     * @param  mixed $value
     * @throws Zend_Filter_Exception If filtering $value is impossible
     * @return mixed
     */
    public function filter($value)
    {
        if ($value != '') {
            $value = ltrim($value, '@');
        }

        return $value;
    }
}