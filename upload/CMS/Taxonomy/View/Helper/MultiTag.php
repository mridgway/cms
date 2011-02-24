<?php

/**
 * Bootstraps required resources for the core module
 *
 * @package     CMS
 * @subpackage  Taxonomy
 * @category    View
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Taxonomy_View_Helper_MultiTag extends ZendX_JQuery_View_Helper_UiWidget
{
    public function multiTag($id, $value = null, array $params = array(), array $attribs = array())
    {
        $attribs = $this->_prepareAttributes($id, $value, $attribs);

        if (!isset($params['source'])) {
            if (isset($params['url'])) {
                $params['source'] = $params['url'];
                unset($params['url']);
            } else if (isset($params['data'])) {
                $params['source'] = $params['data'];
                unset($params['data']);
            } else {
                require_once "ZendX/JQuery/Exception.php";
                throw new ZendX_JQuery_Exception(
                    "Cannot construct AutoComplete field without specifying 'source' field, ".
                    "either an url or an array of elements."
                );
            }
        }

        $params = ZendX_JQuery::encodeJson($params);

        if (!\is_array($value)) {
            $value = array();
        }

        $js = sprintf('%s("#%s").autocomplete(%s).formTag().addTag("'.implode(',', $value).'");',
                ZendX_JQuery_View_Helper_JQuery::getJQueryHandler(),
                $attribs['id'],
                $params,
                $params
        );

        $this->jquery->addOnLoad($js);
        return $this->view->formText($id, '', $attribs);
    }
}