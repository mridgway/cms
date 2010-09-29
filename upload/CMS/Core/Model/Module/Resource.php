<?php

namespace Core\Model\Module;

/**
 * Represents an abstract module resource
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 *
 * @Entity
 * @Table(name="Module_Resource")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="type", type="string")
 * @DiscriminatorMap({
 *      "ContentType"="Core\Model\Module\ContentType",
 *      "BlockType"="Core\Model\Module\BlockType"
 * })
 * @property int $id
 */
abstract class Resource
    extends \Core\Model\AbstractModel
    implements \Zend_Acl_Resource_Interface
{
    /**
     * @var int
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Core\Model\Module
     * @ManyToOne(targetEntity="Core\Model\Module", inversedBy="resources")
     * @JoinColumn(name="module", referencedColumnName="sysname", nullable="false")
     */
    protected $module;

    /**
     * @var string
     * @Column(name="title", type="string", length="100", nullable="false")
     */
    protected $title;

    /**
     * @var string
     * @Column(name="discriminator", type="string", length="100", nullable="false", unique="true")
     */
    protected $discriminator;

    /**
     * @var string
     * @Column(name="class", type="string", length="100", nullable="false", unique="true")
     */
    protected $class;

    /**
     * @var boolean
     * @Column(name="addable", type="boolean", nullable="false")
     */
    protected $addable = false;

    public function __construct($title, $discriminator, $class)
    {
        $this->setTitle($title);
        $this->setDiscriminator($discriminator);
        $this->setClass($class);
    }

    public function setTitle($title)
    {
        $validator = new \Zend_Validate_StringLength(0, 50);
        if (!$validator->isValid($title)) {
            throw new \Core\Model\Exception('Title must be between 0 and 50 characters.');
        }
        $this->title = $title;
        return $this;
    }

    public function setDiscriminator($discriminator)
    {
        $validator = new \Zend_Validate_StringLength(0, 50);
        if (!$validator->isValid($discriminator)) {
            throw new \Core\Model\Exception('Discriminator must be between 0 and 50 characters.');
        }
        $this->discriminator = $discriminator;
        return $this;
    }

    public function setClass($class)
    {
        if (null !== $class) {
            if (!class_exists($class)) {
                throw new \Core\Model\Exception('Class does not exist.');
            }
        }
        $this->class = $class;
        return $this;
    }
}