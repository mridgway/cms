<?php

namespace Core\Model;

/**
 * The central object of the cms that contains information for the current
 * page
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity(repositoryClass="Core\Repository\Page")
 * @property PageRoute $pageRoute
 * @property Core\Model\Layout $layout
 */
class Page extends AbstractPage
{    
    /**
     * @var PageRoute
     * @OneToOne(targetEntity="PageRoute", inversedBy="page",fetch="LAZY",cascade={"remove","detach"})
     */
    protected $pageRoute;

    /**
     * @var Core\Model\Template
     * @ManyToOne(targetEntity="Core\Model\Template", fetch="LAZY")
     * @JoinColumn(name="template_id", referencedColumnName="id", nullable="true")
     */
    protected $template;

    /**
     * @param Layout $layout
     * @param int $weight
     * @param int $left
     * @param int $right
     */
    public function __construct(Layout $layout)
    {
        parent::__construct($layout);
    }

    /**
     *
     * @return string
     */
    public function getURL()
    {
        if (null === $this->pageRoute) {
            return null;
        }
        return $this->pageRoute->getURL();
    }

    /**
     *
     * @param PageRoute $pageRoute
     * @return Page
     */
    public function setPageRoute(PageRoute $pageRoute = null)
    {
        $this->pageRoute = $pageRoute;
        return $this;
    }

    /**
     *
     * @param Template $template
     * @return Page
     */
    public function setTemplate(Template $template)
    {
        $this->template = $template;
        return $this;
    }

    public function getBlock($id)
    {
        foreach($this->blocks as $block) {
            if($id == $block->getId()) {
                return $block;
            }
        }

        throw new \Exception('A block with id ' . $id . ' does not exist on this page.');
    }
    
}