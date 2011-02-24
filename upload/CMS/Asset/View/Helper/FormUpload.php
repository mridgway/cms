<?php

/**
 * Renders an SWFUpload form element
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    View
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Asset_View_Helper_FormUpload extends Zend_View_Helper_FormText
{
    /**
     * Markup info
     *
     * @var array
     */
    protected $_info = array();


    /**
     * __toString() - Magic Method
     *
     * Display the form tag
     *
     * @return string
     */
    public function __toString()
    {
        try {
            extract($this->_info);

            $attribs['class'] = 'default-formupload';
            $group = $attribs['group'];
            unset($attribs['group']);

            $html = $this->formText($name, $value, $attribs, $options, $listsep);
            $html .= '<script type="text/javascript">$(document).ready(function() {$("#'.$this->_normalizeId($name).'").formUpload({group:"'.$group.'"});});</script>';
        } catch (Exception $e) {
            $html = '<div class="">' . $e->getMessage() . '</div>';
        }

        return $html;
    }

    /**
     * Helper accessor
     *
     * @param  string|array $name    If a string, the element name.  If an
     *                               array, all other parameters are ignored,
     *                               and the array elements are used in place of
     *                               added parameters.
     * @param  mixed        $value   The element value.
     * @param  array        $attribs Attributes for the element tag.
     * @return Default_View_Helper_FormUpload *Provides fluid interface*
     */
    public function formUpload($name, $value = null, $attribs = null, $group = 'tmp')
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info);

        $this->_info['name'] = $name;
        $this->_info['value'] = $value;
        $this->_info['attribs'] = $attribs;
        $this->_info['options'] = $options;
        $this->_info['listsep'] = $listsep;
        $this->_info['group'] = $group;

        return $this;
    }
}