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

class ContentActivity extends \Core\Model\AbstractActivity
{
    /**
     * @ManyToOne(targetEntity="Core\Model\Content", inversedBy="activities")
     */
    protected $content;
}