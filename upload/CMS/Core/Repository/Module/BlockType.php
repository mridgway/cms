<?php

namespace Core\Repository\Module;

/**
 * Repository for the view model
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Repository
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class BlockType extends \Doctrine\ORM\EntityRepository
{
    public function findAddableBlockTypes()
    {
        return $this->findByAddable(true);
    }
}