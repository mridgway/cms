<?php

namespace Core\Service;

/**
 * Service for views
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Content extends \Core\Service\AbstractService
{
    public function getContent($id)
    {
        return $this->_em->getReference('Core\Model\Content', $id);
    }
}