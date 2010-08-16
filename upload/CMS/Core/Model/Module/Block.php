<?php
/**
 * Modo CMS
 */

namespace Core\Model\Module;

/**
 * Description of Block
 *
 * @category   Model
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Block.php 297 2010-05-12 13:34:56Z mike $
 *
 * @Entity
 * @Table(name="Module_Block")
 * @property int $id
 */
class Block
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
     * @ManyToOne(targetEntity="Core\Model\Module")
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

    /**
     * @return string
     */
    public function getResourceId()
    {
        return $this->getModule()->getResourceId() . '.Block.' . $this->getDiscriminator();
    }
}