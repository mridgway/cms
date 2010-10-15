<?php

namespace Core\Model\Module;

/**
 * Represents an activity type that is installed with a module
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity
 * @Table(name="Module_ActivityType")
 * @property int $id
 */
class ActivityType
    extends Resource
{

    protected $resourceString = 'Activity';
}