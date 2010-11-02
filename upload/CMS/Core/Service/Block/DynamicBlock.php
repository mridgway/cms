<?php

namespace Core\Service\Block;

/**
 * Controller for actions on pages
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */

class DynamicBlock extends \Core\Service\AbstractService
{
    /**
     * Finds addable block types.
     *
     * @return array
     */
    public function getAddableBlockTypes()
    {
        return $this->_em->getRepository('Core\Model\Module\BlockType')->findAddableBlockTypes();
    }

    /**
     * Creates a new instance of a block.
     * 
     * @param integer $id
     * @return \Core\Model\Block\DynamicBlock
     */
    public function create($id)
    {
        $blockType = $this->_em->find('Core\Model\Module\BlockType', $id);
        $view = $blockType->getView('default');
        return $blockType->createInstance(array($view));
    }
}