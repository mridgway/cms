<?php
/**
 * Modo CMS
 */

namespace Core\Model;

/**
 * Description of Module
 *
 * @category   Model
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Module.php 297 2010-05-12 13:34:56Z mike $
 *
 * @Entity(repositoryClass="Core\Repository\Module")
 *
 * @property string $name;
 * @property Core\Model\Module\Block[] $blockTypes
 * @property Core\Model\Module\Content[] $contentTypes
 */
class Module
    extends \Core\Model\AbstractModel
    implements \Zend_Acl_Resource_Interface
{
    /**
     * @var string
     * @Id @Column(name="sysname", type="string", length="50", nullable="false")
     */
    protected $sysname;

    /**
     * @var string
     * @Column(name="title", type="string", length="100", nullable="false")
     */
    protected $title;

    /**
     * @var array
     * @OneToMany(targetEntity="Core\Model\Module\Block", mappedBy="module", cascade={"persist"}, fetch="EAGER")
     */
    protected $blockTypes;

    /**
     * @var array
     * @OneToMany(targetEntity="Core\Model\Module\Content", mappedBy="module", cascade={"persist"}, fetch="EAGER")
     */
    protected $contentTypes;

    public function __construct($sysname, $title='')
    {
        $this->setSysname($sysname);
        if ($title == '') {
            $title = $sysname;
        }
        $this->setTitle($title);
        $this->blockTypes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contentTypes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function setSysname($sysname)
    {
        $validator = new \Zend_Validate_StringLength(0, 50);
        if (!$validator->isValid($sysname)) {
            throw new \Core\Model\Exception('Name must be between 0 and 50 characters.');
        }
        $this->sysname = $sysname;
        return $this;
    }

    public function setTitle($title)
    {
        $validator = new \Zend_Validate_StringLength(0, 100);
        if (!$validator->isValid($title)) {
            throw new \Core\Model\Exception('Name must be between 0 and 100 characters.');
        }
        $this->title = $title;
        return $this;
    }

    public function addBlock(\Core\Model\Module\Block $block)
    {
        $block->module = $this;
        $this->blockTypes[$block->discriminator] = $block;
    }

    public function addContent(\Core\Model\Module\Content $content)
    {
        $content->module = $this;
        $this->contentTypes[$content->discriminator] = $content;
    }

    public function getBlockType($name)
    {
        return $this->blockTypes[$name];
    }

    public function getContentType($name)
    {
        return $this->contentTypes[$name];
    }

    public function getBlockDiscriminatorMap()
    {
        $map = array();
        foreach ($this->blockTypes AS $block) {
            $map[$block->discriminator] = $block->class;
        }
        return $map;
    }

    public function getContentDiscriminatorMap()
    {
        $map = array();
        foreach ($this->contentTypes AS $contentTypes) {
            $map[$contentTypes->discriminator] = $contentTypes->class;
        }
        return $map;
    }

    public function getResourceId()
    {
        return $this->getSysname();
    }
}