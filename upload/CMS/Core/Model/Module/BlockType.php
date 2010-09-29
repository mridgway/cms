<?php

namespace Core\Model\Module;

/**
 * Represents a block type that is installed with a module
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 *
 * @Entity
 * @Table(name="Module_BlockType")
 * @property int $id
 */
class BlockType
    extends Resource
{

    /**
     * @return string
     */
    public function getResourceId()
    {
        return $this->getModule()->getResourceId() . '.Block.' . $this->getDiscriminator();
    }
}