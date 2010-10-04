<?php

namespace Core\Model\Module;

/**
 * Represents a block type that is installed with a module
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity(repositoryClass="Core\Repository\Module\BlockType")
 * @Table(name="Module_BlockType")
 * @property int $id
 */
class BlockType
    extends Resource
{

    protected $resourceString = 'Block';
}