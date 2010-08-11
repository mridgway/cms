<?php
/**
 * Modo CMS
 */

namespace Modo\Orm\Model;

/**
 * Interface for uniquely identifiable content
 *
 * @category   Modo
 * @package    Orm
 * @subpackage Model
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: IdentifiableInterface.php 70 2010-01-08 17:02:45Z court $
 */
interface IdentifiableInterface
{
    /**
     * Get the unique identifier
     *
     * @return mixed
     */
    public function getIdentifier();
}