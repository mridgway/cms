<?php

namespace Core\Service;

/**
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class ActivityService extends AbstractService
{
    public function addPageActivity($page)
    {
        $activity = new \Core\Model\Activity\PageActivity($page, \Core\Auth\Auth::getInstance()->getIdentity());
        $this->_em->persist($activity);
        $this->_em->flush();
    }
}