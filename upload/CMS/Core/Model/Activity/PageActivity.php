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

class PageActivity extends \Core\Model\AbstractActivity
{
    /**
     * @OneToOne(targetEntity="Core\Model\Page")
     */
    protected $page;

    /**
     * @OneToOne(targetEntity="User\Model\User")
     */
    protected $user;

    public function __construct(\Core\Model\Page $page, \User\Model\User $user)
    {
        parent::__construct();
        $this->page = $page;
        $this->user = $user;
    }
}