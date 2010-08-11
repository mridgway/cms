<?php
/**
 * Modo CMS
 */

namespace Modo\Form;

/**
 * Form display group
 *
 * @category   Modo
 * @package    Form
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: DisplayGroup.php 102 2010-01-14 22:41:49Z court $
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
