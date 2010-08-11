<?php
/**
 * Modo CMS
 */

namespace Modo\Form\Element;

/**
 * Button form element
 *
 * @category   Modo
 * @package    Form
 * @subpackage Element
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Hidden.php 126 2010-01-27 16:24:24Z mike $
 */
class Hidden extends \Zend_Form_Element_Hidden
{

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
            $this->addDecorator('ViewHelper');
        }
    }
}