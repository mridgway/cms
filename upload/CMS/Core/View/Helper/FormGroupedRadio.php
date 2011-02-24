<?php

class Core_View_Helper_FormGroupedRadio extends \Zend_View_Helper_FormRadio
{

    /**
     * Generates a set of radio button elements.
     *
     * @access public
     *
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are extracted in place of added parameters.
     *
     * @param mixed $value The radio value to mark as 'checked'.
     *
     * @param array $options An array of key-value pairs where the array
     * key is the radio value, and the array value is the radio text.
     *
     * @param array|string $attribs Attributes added to each radio.
     *
     * @return string The radio buttons XHTML.
     */
    public function formGroupedRadio($name, $value = null, $attribs = null,
        $options = null, $listsep = "<br />\n")
    {
        $attribs['listsep'] = '';
        $output = '';

        if (isset($attribs['columns']) && $attribs['columns']) {
            // assemble the columns
            $numColumns = $attribs['columns'];
            $numRows = ceil(count($options) / $numColumns);
            $lastRowCount = count($options) % $numColumns;
            $columns = array();

            $column = 0;
            foreach ($options AS $key => $option) {
                $columns[$column][$key] = $option;
                if (count($columns[$column]) == $numRows) {
                    ++$column;
                }
            }
            $output .= '<ul class="columnlist">';
            foreach ($columns AS $options) {
                $output .= '<li class="column">';
                $output .= $this->renderList($name, $value, $attribs, $options, $listsep);
                $output .= '</li>';
            }
            $output .= '</ul>';
        } else {
            $output .= $this->renderList($name, $value, $attribs, $options, $listsep);
        }
        return $output;
    }

    public function renderList($name, $value = null, $attribs = null, $options = null, $listsep = '')
    {
        $output = '';
        $output .= '<ul class="groupedinputlist">';
        foreach ($options AS $category => $option) {
            $output .= '<li class="inputcategory">';
            $output .= '<h4>' . $category . '</h4>';
            $output .= '<ul class="inputlist"><li class="radio">';
            $output .= $this->formRadio($name, $value, $attribs, $option, '</li><li>');
            $output .= '</li></ul>';
            $output .= '</li>';
        }
        $output .= '</ul>';

        return $output;
    }
}