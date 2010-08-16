<?php
/**
 * Modo CMS
 */

namespace Core\Model\Block\Config\Property;

/**
 *
 * @category   Model
 * @package    Modo
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Text.php 297 2010-05-12 13:34:56Z mike $
 */
class Text extends \Core\Model\Block\Config\Property implements \Core\Model\Block\ConfigurableInterface
{
    public function getConfigurationField()
    {
        $field = new \Core\Form\Element\Text($this->getName());
        $field->setLabel($this->name);
        $field->setValue($this->default);
        $field->setRequired(true);

        return $field;
    }
}