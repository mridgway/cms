<?php

/**
 * jQuery Autocomplete View Helper with a hidden field for value (id)
 *
 * @uses 	   Zend_Json, Zend_View_Helper_FormText
 * @package    ZendX_JQuery
 * @subpackage View
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZendX_JQuery_View_Helper_AutoCompleteHidden extends ZendX_JQuery_View_Helper_UiWidget
{
    /**
     * @link   http://docs.jquery.com/UI/Autocomplete
     * @throws ZendX_JQuery_Exception
     * @param  String $id
     * @param  String $value
     * @param  array $params
     * @param  array $attribs
     * @return String
     */
    public function autoCompleteHidden($id, $value = null, array $params = array(), array $attribs = array())
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
        if (!isset($params['hiddenFieldName'])) {
            throw new ZendX_JQuery_Exception('Must specify the hidden field ID.');
        }

        $hiddenFieldName = $params['hiddenFieldName'];
        unset($params['hiddenFieldName']);

        $selectCallback = new Zend_Json_Expr(sprintf('function (event, ui) {
            %s("#%s").val(ui.item.id);
        }',
            ZendX_JQuery_View_Helper_JQuery::getJQueryHandler(),
            $hiddenFieldName
        ));

        $changeCallback = new Zend_Json_Expr(sprintf('function (event, ui) {
            if ("" == $(this).val()) {
                %s("#%s").val("");
            }
        }',
            ZendX_JQuery_View_Helper_JQuery::getJQueryHandler(),
            $hiddenFieldName
        ));

        $params['select'] = $selectCallback;
        $params['change'] = $changeCallback;

        $params = ZendX_JQuery::encodeJson($params);

        $js = sprintf('%s("#%s").autocomplete(%s);',
                ZendX_JQuery_View_Helper_JQuery::getJQueryHandler(),
                $attribs['id'],
                $params
        );

        $this->jquery->addOnLoad($js);

        return $this->view->formText($id, $value, $attribs);
    }
}