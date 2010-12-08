<?php

namespace Taxonomy\Model;

use \Core\Model;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Represents a grouping of taxonomy terms
 *
 * @package     CMS
 * @subpackage  Taxonomy
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity
 * @Table(name="TaxonomyVocabulary")
 *
 * @property integer $id
 * @property string $sysname
 * @property string $name
 * @property string $description
 * @property ArrayCollecion $terms
 */
class Vocabulary
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
    protected $description;

    /**
     * @var ArrayCollection
     * @OneToMany(targetEntity="Taxonomy\Model\Term", mappedBy="vocabulary")
     */
    protected $terms;

    public function __construct($name, $sysname, $description = '')
    {
        $this->setName($name);
        $this->setSysname($sysname);
        $this->setDescription($description);
        $this->setTerms(new ArrayCollection());
    }

    public function toArray($includes = null)
    {
        return $this->_toArray($includes);
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

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function addTerm(Term $term)
    {
        $this->terms->add($term);
    }

    public function getTerms()
    {
        return $this->terms;
    }

    public function setTerms($terms)
    {
        $this->terms = $terms;
    }
}