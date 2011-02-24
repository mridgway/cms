<?php

namespace Core\Model\Activity;

/**
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity
 */
abstract class ContentActivity extends \Core\Model\AbstractActivity
{
    /**
     * @ManyToOne(targetEntity="Core\Model\Content", inversedBy="activities")
     * @JoinColumn(nullable=false, onDelete="CASCADE")
     */
    protected $content;

    public function __construct(\Core\Model\Content $content)
    {
        parent::__construct();
        $this->setContent($content);
        $this->updateLocation();
    }

    abstract public function updateLocation();

    public function getContent()
    {
        return $this->content;
    }

    protected function setContent($content)
    {
        $this->content = $content;
    }
}