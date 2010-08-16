<?php
/**
 * Modo CMS
 */

namespace Core\Form;

/**
 * Base Form
 *
 * @category   Form
 * @package    Modo
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: AbstractForm.php 206 2010-02-25 15:35:09Z mike $
 */
class AbstractForm extends \Zend_Form
{
    /**
     * {@inheritdoc}
     * 
     * @var string
     */
    protected $_defaultDisplayGroupClass = 'Core\Form\DisplayGroup';

    
    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('FormElements')
                 ->addDecorator('Form');
        }
    }

    /**
     * Sets form elements to the value of matched properties in given object
     *
     * @todo Change method name to be more consistent.  Method should consider
     *       subforms.  If possible, utilize existing populate functionality.
     *
     * @param mixed $object
     */
    public function setObject($object)
    {
        foreach($this->getElements() as $element) {
            if (isset($object->{$element->getName()})) {
                $element->setValue($object->{$element->getName()});
            }
        }
    }

    /**
     * Used to traverse object properties based on the element's underscores
     *
     * @param object $element
     * @return mixed
     */
    public function _traverseElementObject($element)
    {
        $properties = array_reverse(explode('_', $element->getName()));

        // traverse object
        $curObject = $object;
        $property = array_pop($properties);
        while(isset($curObject->{$property}) && is_object($curObject->{$property}) && !empty($properties)) {
            $curObject = $curObject->{$property};
            $property = array_pop($properties);
        }

        if (isset($curObject->{$property})) {
            return $element->setValue($curObject->{$property});
        } else {
            return null;
        }
    }
}