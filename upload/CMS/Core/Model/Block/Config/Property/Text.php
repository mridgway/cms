<?php

namespace Core\Model\Block\Config\Property;

/**
 * A configuration property whose value is a string
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Text
    extends \Core\Model\Block\Config\Property
    implements \Core\Model\Block\ConfigurableInterface
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