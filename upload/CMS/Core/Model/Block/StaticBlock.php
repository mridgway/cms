<?php

namespace Core\Model\Block;

/**
 * A block that displays a specific content item
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 *
 * @Entity(repositoryClass="Core\Repository\StaticBlock")
 * @HasLifecycleCallbacks
 * @property \Core\Model\Content $content
 */
class StaticBlock extends \Core\Model\Block
{

    /**
     * @var \Core\Model\Content
     * @ManyToOne(targetEntity="Core\Model\Content")
     * @JoinColumn(name="content_id", referencedColumnName="id", nullable="true")
     */
    protected $content;

    public function __construct(\Core\Model\Content $content, \Core\Model\Module\View $view)
    {
        parent::__construct($view);
        $this->setContent($content);
    }
    
    public function getViewInstance()
    {
        $instance = parent::getViewInstance();
        $instance->assign('content', $this->content);
        return $instance;
    }

    /**
     * Static blocks don't have configs.  But, they have content whose properties may need
     * to be used in another block. So, let's just get the content's property.
     *
     * @param string $name
     */
    public function getConfigValue($name)
    {
        return $this->content->$name;
    }

    /**
     *
     * @param Core\Model\Content $content
     * @return StaticBlock
     */
    public function setContent(\Core\Model\Content $content)
    {
        $this->content = $content;
        return $this;
    }

    public function canView($role)
    {
        return parent::canView($role) && $this->content->canView($role);
    }

    /**
     * Ew
     * @todo find a way to make this not suck
     */
    public function canEdit($role)
    {
        $modules = \Core\Module\Registry::getInstance()->getDatabaseStorage()->getModules();
        foreach ($modules AS $module) {
            foreach($module->contentTypes AS $type) {
                if ($type->class == get_class($this->content)) {
                    if ($type->controller) {
                        if (class_exists($type->controller) && method_exists($type->controller, 'editAction')) {
                            return (parent::canEdit($role) && $this->content->canEdit($role));
                        }
                    }
                }
            }
        }
        return false;
    }

    public function canConfigure($role)
    {
        return false;
    }

    public function canDelete($role)
    {
        if ($this->content instanceof \Core\Model\Content\Placeholder) {
            return false;
        }
        return parent::canDelete($role);
    }
}