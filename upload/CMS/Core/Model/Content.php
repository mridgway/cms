<?php

namespace Core\Model;

use \Core\Model;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * A base class for any kind of site content
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity
 * @Table(name="content")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="type", type="string")
 *
 * @property integer $id
 * @property \Core\Model\Block[] $blocks
 * @property \Core\Model\Page $dependentPage
 * @property array $activities
 */
abstract class Content
    extends Model\AbstractModel
    implements \Zend_Acl_Resource_Interface
{
    /**
     * @var integer
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /**
     * @var User\Model\User
     * @ManyToOne(targetEntity="User\Model\User")
     * @JoinColumn(referencedColumnName="id", nullable="true")
     */
    protected $author;

    /**
     * @var string
     * @Column(type="string", nullable="false")
     */
    protected $authorName = '';

    /**
     * @var DateTime
     * @Column(type="datetime")
     */
    protected $creationDate;

    /**
     * @var DateTime
     * @Column(type="datetime")
     */
    protected $modificationDate;

    /**
     * @var array
     * @ManyToMany(targetEntity="Taxonomy\Model\Term")
     * @JoinTable(name="content_tags",
     *      joinColumns={@JoinColumn(name="content_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="term_id", referencedColumnName="id")}
     *      )
     */
    protected $tags;

    /**
     *
     * @var Taxonomy\Model\Term
     * @ManyToOne(targetEntity="Taxonomy\Model\Term")
     * @JoinColumn(referencedColumnName="id", nullable = "false")
     */
    protected $status;

    /**
     *
     * @var boolean
     * @Column(type="boolean")
     */
    protected $isFeatured = false;

    /**
     * The main page that this content shows up on. Example: blog article page. This is not
     * required, since many content types don't get their own page. This page relies on this content
     * and this content relies on this page. Deletions should probably be bidirectionally cascaded.
     *
     * @var Core\Model\AbstractPage
     * @ManyToOne(targetEntity="Core\Model\Page", cascade={"delete"})
     * @JoinColumn(name="page_id", referencedColumnName="id", nullable="true")
     */
    protected $dependentPage;

    /**
     * @OneToMany(targetEntity="Core\Model\Activity\ContentActivity", mappedBy="content", cascade={"delete"})
     */
    protected $activities;

    public function __construct()
    {
        $this->creationDate = new \DateTime;
        $this->modificationDate = new \DateTime;
        $this->tags = new ArrayCollection();
        $this->dependentPage = null;
    }

    public function toArray($includes = null)
    {
        return $this->_toArray($includes);
    }

    public function fromArray($data)
    {
        $this->_setIfSet('authorName', $data);
        $this->_setIfSet('isActive', $data);
        $this->_setIfSet('isFeatured', $data);
        $this->_setIfSet('modificationDate', $data);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function getAuthorName()
    {
        return $this->authorName;
    }

    public function setAuthorName($authorName)
    {
        $this->authorName = $authorName;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    public function getModificationDate()
    {
        return $this->modificationDate;
    }

    public function setModificationDate($modificationDate)
    {
        $this->modificationDate = $modificationDate;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getIsFeatured()
    {
        return $this->isFeatured;
    }

    public function setIsFeatured($isFeatured)
    {
        $this->isFeatured = $isFeatured;
    }

    public function getActivities()
    {
        return $this->activities;
    }

    public function setActivities($activities)
    {
        $this->activities = $activities;
    }

    /**
     * @return \Core\Model\Page
     */
    public function getDependentPage()
    {
        return $this->dependentPage;
    }

    /**
     * @param Page $page
     */
    public function setDependentPage(\Core\Model\Page $page)
    {
        $this->dependentPage = $page;
        $page->addDependentContent($this);
    }

    /**
     * Returns the primary page's URL
     *
     * @return string
     */
    public function getURL()
    {
        if (null === $this->dependentPage) {
            return null;
        }
        return $this->dependentPage->getURL();
    }

    /**
     * @return string
     */
    public function getResourceId()
    {
        return 'Content.' . $this->getId();
    }

    public function canView($role)
    {
        return \Zend_Registry::get('acl')->isAllowed($role, $this, 'view');
    }

    public function canAdd($role)
    {
        return \Zend_Registry::get('acl')->isAllowed($role, $this, 'add');
    }

    public function canEdit($role)
    {
        return \Zend_Registry::get('acl')->isAllowed($role, $this, 'edit');
    }

    public function canDelete($role)
    {
        return \Zend_Registry::get('acl')->isAllowed($role, $this, 'delete');
    }
}