<?php

namespace Core\Model\Block;

/**
 * A block that displays a specific content item
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity(repositoryClass="Core\Repository\StaticBlock")
 * @HasLifecycleCallbacks
 * @property \Core\Model\Content $content
 */
class StaticBlock extends \Core\Model\Block
{

    /**
     * @var \Core\Model\Content
     * @ManyToOne(targetEntity="Core\Model\Content", cascade={"persist"})
     * @JoinColumn(name="content_id", referencedColumnName="id", nullable="true", onDelete="CASCADE")
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
        if ($this->content instanceof \Core\Model\Content\Placeholder) {
            return null;
        }
        
        $nameParts = explode('.', $name);
        $instance = $this->content;
        foreach ($nameParts AS $property) {
            if (null == $instance || !\property_exists($instance, $property)) {
                return null;
            }
            $instance = $instance->$property;
        }
        return $instance;
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
        return parent::canView($role) && $this->getContent()->canView($role);
    }

    /**
     * @todo optimize this
     */
    public function canEdit($role)
    {
        $modules = \Core\Module\Registry::getInstance()->getDatabaseStorage()->getModules();
        foreach ($modules AS $module) {
            foreach($module->contentTypes AS $type) {
                if ($this->content instanceof $type->class) {
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
        if ($this->content instanceof \Core\Model\Content\Placeholder
                || $this->getPage()->getDependentContent()->contains($this->getContent())) {
            return false;
        }
        return parent::canDelete($role);
    }
}