<?php
/**
 * Modo CMS
 */

namespace Modo\Orm\Model;

/**
 * Description of RevisionableInterface
 *
 * @category   Modo
 * @package    Orm
 * @subpackage Model
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: RevisionableInterface.php 70 2010-01-08 17:02:45Z court $
 */
interface RevisionableInterface
{
    /**
     * Add a new change to the revision
     *
     * @param Change $change
     */
    public function addChange(ChangeableInterface $change);

    /**
     * Get the changes from the revision
     *
     * @return mixed
     */
    public function getChanges();

    /**
     * Set the date of the revision
     *
     * @param mixed $date
     */
    public function setDate($date);
}