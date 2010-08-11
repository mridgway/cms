<?php
/**
 * Modo CMS
 */

namespace Core\Model\Revision;

/**
 * A Change's value
 *
 * @see \Core\Model\Revision\Change
 *
 * @category   Revision
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Value.php 297 2010-05-12 13:34:56Z mike $
 *
 * @Entity
 * @Table(name="Revision_Change_Value")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="value_type", type="string")
 * @DiscriminatorMap({"String"="Core\Model\Revision\Value\String", "Text"="Core\Model\Revision\Value\Text", "Integer"="Core\Model\Revision\Value\Integer"})
 *
 * @property int $id
 */
abstract class Value
{
    /**
     * @Id
     * @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;
}