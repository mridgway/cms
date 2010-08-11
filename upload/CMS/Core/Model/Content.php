<?php

namespace Core\Model;

use \Modo\Orm\Model;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * A position on a page that contains content
 *
 * @category   Content
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Content.php 297 2010-05-12 13:34:56Z mike $
 * 
 * @Entity
 * @Table(name="Content")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="type", type="string")
 *
 * @property integer $id
 * @property \Core\Model\Block[] $blocks
 * @property \Core\Model\Page $dependentPage
 */
abstract class Content 
    extends Model\AbstractModel
    implements Model\VersionableInterface,
               \Zend_Acl_Resource_Interface
{
    /**
     * @var integer
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /**
     * The main page that this content shows up on. Example: blog article page. This is not
     * required, since many content types don't get their own page. This page relies on this content
     * and this content relies on this page. Deletions should probably be bidirectionally cascaded.
     * 
     * @var Core\Model\AbstractPage
     * @ManyToOne(targetEntity="Core\Model\AbstractPage", cascade={"delete"})
     * @JoinColumn(name="page_id", referencedColumnName="id", nullable="true")
     */
    protected $dependentPage;

    public function __construct()
    {
        $this->dependentPage = null;
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
     * @param Page $page
     */
    public function setDependentPage(\Core\Model\Page $page)
    {
        $this->dependentPage = $page;
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