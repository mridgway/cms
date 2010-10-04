<?php

namespace Core\Form;

/**
 * Form display group
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class DisplayGroup extends \Zend_Form_DisplayGroup
{
    /**
     * Get the default form group decorators
     * 
     * @return array
     */
    public static function getDefaultDecorators()
    {
        return array(
            'FormElements',
            array('Description', array('placement' => 'prepend',
                                       'class'     => 'description')),
            'Fieldset',
        );
    }

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
            $this->setDecorators(self::getDefaultDecorators());
        }
    }
}
