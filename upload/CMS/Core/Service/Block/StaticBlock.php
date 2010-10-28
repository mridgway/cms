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

class StaticBlock extends \Core\Service\AbstractService
{
    /**
     * @var \Core\Service\Module
     */
    protected $_moduleService;

    public function create(\Core\Model\Content $content = null, \Core\Model\Module\View $view = null)
    {
        if(null === $content) {
            $content = new \Core\Model\Content\Text(null, 'put content here', false);
        }
        
        if(null === $view) {
            $view = $this->getModuleService()->getView('Core', 'Text', 'default');
        }
        
        $block = new \Core\Model\Block\StaticBlock($content, $view);

        return $block;
    }

    public function setModuleService(\Core\Service\Module $moduleService)
    {
        $this->_moduleService = $moduleService;
    }

    public function getModuleService()
    {
        return $this->_moduleService;
    }
}