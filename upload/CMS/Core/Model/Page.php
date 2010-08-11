<?php
/**
 * Modo CMS
 */

namespace Core\Model;

/**
 * The central object of the Modo CMS that contains information for the current page
 *
 * @category   Page
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Page.php 297 2010-05-12 13:34:56Z mike $
 *
 * @Entity(repositoryClass="Core\Repository\Page")
 * @property PageRoute $primaryPageRoute
 * @property int $weight
 * @property int $left
 * @property int $right
 * @property int $parent
 * @property Core\Model\Layout $layout
 */
class Page extends AbstractPage
{
    /**
     * @var PageRoute
     * @OneToOne(targetEntity="PageRoute", fetch="LAZY")
     * @JoinColumn(name="primary_pageroute", referencedColumnName="id", nullable="true")
     */
    protected $primaryPageRoute;

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
        if (null === $this->primaryPageRoute) {
            return null;
        }
        return $this->primaryPageRoute->getURL();
    }

    /**
     *
     * @param PageRoute $pageRoute
     * @return Page
     */
    public function setPrimaryPageRoute(PageRoute $pageRoute = null)
    {
        $this->primaryPageRoute = $pageRoute;
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
}