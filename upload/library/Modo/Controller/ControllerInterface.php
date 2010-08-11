<?php
/**
 * Modo CMS
 */

namespace Modo\Controller;

/**
 * Provides an interface for add, edit, and delete actions.
 *
 * @category   Content
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: ControllerInterface.php 197 2010-02-19 19:33:21Z mike $
 */
interface ControllerInterface
{
    public function addAction();
    public function editAction(\Core\Model\Block $block);
    public function deleteAction(\Core\Model\Block $block);

    public function setRequest(\Zend_Controller_Request_Http $request);
    public function setEntityManager(\Doctrine\ORM\EntityManager $em);
}