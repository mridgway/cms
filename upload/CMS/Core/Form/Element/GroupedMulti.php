<?php

namespace Core\Form\Element;

class GroupedMulti extends \Zend_Form_Element_Multi
{
    /**
     * Add an option
     *
     * @param  string $option
     * @param  string $value
     * @return Zend_Form_Element_Multi
     */
    public function addMultiOption($option, $value = '', $group = 'default')
    {
        $option  = (string) $option;
        $this->_getMultiOptions();
        if (!$this->_translateOption($option, $value, $group)) {
            $this->options[$group][$option] = $value;
        }

        return $this;
    }

    /**
     * Add many options at once
     *
     * @param  array $options
     * @return Zend_Form_Element_Multi
     */
    public function addMultiOptions(array $options)
    {
        foreach ($options AS $key => $group) {
            foreach ($group as $option => $value) {
                if (is_array($value)
                    && array_key_exists('key', $value)
                    && array_key_exists('value', $value)
                ) {
                    $this->addMultiOption($value['key'], $value['value'], $key);
                } else {
                    $this->addMultiOption($option, $value, $key);
                }
            }
        }
        return $this;
    }

    /**
     * Retrieve single multi option
     *
     * @param  string $option
     * @return mixed
     */
    public function getMultiOption($option)
    {
        $option  = (string) $option;
        $this->_getMultiOptions();
        foreach ($this->options as $key => $group) {
            if (isset($this->options[$key][$option])) {
                $this->_translateOption($option, $this->options[$key][$option], $key);
                return $this->options[$key][$option];
            }
        }

        return null;
    }

    /**
     * Retrieve options
     *
     * @return array
     */
    public function getMultiOptions()
    {
        $this->_getMultiOptions();
        foreach ($this->options as $key => $group) {
            foreach ($group as $option => $value) {
                $this->_translateOption($option, $value, $key);
            }
        }
        return $this->options;
    }

    /**
     * Remove a single multi option
     *
     * @param  string $option
     * @return bool
     */
    public function removeMultiOption($option)
    {
        $option  = (string) $option;
        $this->_getMultiOptions();
        foreach($this->options as $key => $group)
        if (isset($this->options[$key][$option])) {
            unset($this->options[$key][$option]);
            if (isset($this->_translated[$key][$option])) {
                unset($this->_translated[$key][$option]);
            }
            return true;
        }

        return false;
    }

    /**
     * Is the value provided valid?
     *
     * Autoregisters InArray validator if necessary.
     *
     * @param  string $value
     * @param  mixed $context
     * @return bool
     */
    public function isValid($value, $context = null)
    {
        if ($this->registerInArrayValidator()) {
            if (!$this->getValidator('InArray')) {
                $multiOptions = $this->getMultiOptions();
                $options      = array();

                foreach ($multiOptions as $key => $group) {
                    foreach ($group as $opt_value => $opt_label) {
                        // optgroup instead of option label
                        if (is_array($opt_label)) {
                            $options = array_merge($options, array_keys($opt_label));
                        }
                        else {
                            $options[] = $opt_value;
                        }
                    }
                }

                $this->addValidator(
                    'InArray',
                    true,
                    array($options)
                );
            }
        }
        return parent::isValid($value, $context);
    }

    /**
     * Translate an option
     *
     * @param  string $option
     * @param  string $value
     * @return bool
     */
    protected function _translateOption($option, $value, $group = 'default')
    {
        if ($this->translatorIsDisabled()) {
            return false;
        }

        if (!isset($this->_translated[$group][$option]) && !empty($value)) {
            $this->options[$group][$option] = $this->_translateValue($value);
            if ($this->options[$group][$option] === $value) {
                return false;
            }
            $this->_translated[$group][$option] = true;
            return true;
        }

        return false;
    }
}