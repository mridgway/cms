<?php

namespace Taxonomy\Model;

use \Core\Model;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * A term and definition
 *
 * @package     CMS
 * @subpackage  Taxonomy
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity
 * @Table(name="TaxonomyTerm")
 *
 * @property integer $id
 */
class Term
    extends Model\AbstractModel
{
    /**
     * @var integer
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /**
     * @var string
     * @Column(type="string", unique="true", nullable="true")
     */
    protected $sysname;

    /**
     * @var string
     * @Column(type="string", nullable="false")
     */
    protected $name;

    /**
     * @var string
     * @Column(type="string")
     */
    protected $definition;

    /**
     * @var Taxonomy\Model\Vocabulary
     * @ManyToOne(targetEntity="Taxonomy\Model\Vocabulary", inversedBy="terms")
     * @JoinColumn(referencedColumnName="id")
     */
    protected $vocabulary;

    public function __construct($sysname, $name, $definition = '')
    {
        $this->setSysname($sysname);
        $this->setName($name);
        $this->setDefinition($definition);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSysname()
    {
        return $this->sysname;
    }

    public function setSysname($sysname)
    {
        $this->sysname = $sysname;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getDefinition()
    {
        return $this->definition;
    }

    public function setDefinition($definition)
    {
        $this->definition = $definition;
    }

    public function getVocabulary()
    {
        return $this->vocabulary;
    }

    public function setVocabulary(Vocabulary $vocabulary)
    {
        $this->vocabulary = $vocabulary;
    }
}