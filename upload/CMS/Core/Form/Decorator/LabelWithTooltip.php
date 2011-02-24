<?php

namespace Core\Form\Decorator;

class LabelWithTooltip extends \Zend_Form_Decorator_Label
{

    /**
     * Decorate content and/or element
     *
     * @param  string $content
     * @return string
     * @throws Zend_Form_Decorator_Exception when unimplemented
     */
    public function getTooltip()
    {
    }


    /**
     * Render a label
     *
     * @param  string $content
     * @return string
     */
    public function render($content)
    {
        $element = $this->getElement();
        $view    = $element->getView();
        if (null === $view) {
            return $content;
        }

        $label     = $this->getLabel();
        $separator = $this->getSeparator();
        $placement = $this->getPlacement();
        $tag       = $this->getTag();
        $id        = $this->getId();
        $class     = $this->getClass();
        $options   = $this->getOptions();


        if (empty($label) && empty($tag)) {
            return $content;
        }

        if (!empty($label)) {
            $options['class'] = $class;
            $label = $view->formLabelWithTooltip($element->getFullyQualifiedName(), trim($label) . $this->getTooltip(), $options);
        } else {
            $label = '&nbsp;';
        }

        if (null !== $tag) {
            $decorator = new \Zend_Form_Decorator_HtmlTag();
            $decorator->setOptions(array('tag' => $tag,
                                         'id'  => $this->getElement()->getName() . '-label'));
            $label = $decorator->render($label);
        }

        switch ($placement) {
            case self::APPEND:
                return $content . $separator . $label;
            case self::PREPEND:
                return $label . $separator . $content;
        }
    }
}
        