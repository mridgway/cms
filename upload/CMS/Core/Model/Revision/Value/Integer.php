<?php
/**
 * Modo CMS
 */

namespace Core\Model\Revision\Value;

/**
 * A textual value that can be represented as an integer
 *
 * @category   Revision
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Integer.php 297 2010-05-12 13:34:56Z mike $
 *
 * @Entity
 * @Table(name="Revision_Change_Value_Integer")
 * 
 * @property int $value
 */
class Integer extends \Core\Model\Revision\Value
{
    /**
     * @Column(name="value", type="integer", nullable="true")
     */
    public $value;

    public function __construct ($val)
    {
        $this->value_type = 'Integer';
        $this->value = $val;
    }
}