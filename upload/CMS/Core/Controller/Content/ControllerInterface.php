<?php

namespace Core\Controller\Content;

/**
 * Provides an interface for add, edit, and delete actions.
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Controller
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
interface ControllerInterface
{
    public function addAction();
    public function editAction(\Core\Model\Block $block);
    public function deleteAction(\Core\Model\Block $block);

    public function setRequest(\Zend_Controller_Request_Http $request);
}