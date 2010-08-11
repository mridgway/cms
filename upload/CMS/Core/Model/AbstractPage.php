<?php
/**
 * Modo CMS
 */

namespace Core\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of AbstractPage
 *
 * @category   Model
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: AbstractPage.php 302 2010-05-19 19:22:02Z mike $
 *
 * @Entity
 * @Table(name="AbstractPage")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="type", type="string")
 * @DiscriminatorMap({"Page"="Core\Model\Page", "Template"="Core\Model\Template"})
 * 
 * @property int $id
 * @property string $title
 * @property string $description
 * @property \Core\Model\Page $parent
 * @property \Core\Model\Block[] $blocks
 * @property \Core\Model\Layout $layout
 * @property \Core\Model\Content $dependentContent
 */
abstract class AbstractPage
    extends \Modo\Orm\Model\AbstractModel
    implements \Modo\Orm\Model\VersionableInterface,
               \Zend_Acl_Resource_Interface
{
    /**
     * @var integer
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @Column(name="title", type="string", length="255", nullable="true")
     */
    protected $title;

    /**
     * @var string
     * @Column(name="description", type="string", length="500", nullable="true")
     */
    protected $description;

    /**
     * @OneToMany(targetEntity="Core\Model\Block", mappedBy="page", fetch="LAZY", cascade={"all", "persist"})
     * @OrderBy({"weight"="ASC"})
     */
    protected $blocks;

    /**
     * @ManyToOne(targetEntity="Core\Model\Layout")
     * @JoinColumn(name="layout_id", referencedColumnName="sysname", nullable="false")
     */
    protected $layout;

    /**
     * @OneToMany(targetEntity="Core\Model\Content", mappedBy="dependentPage")
     */
    protected $dependentContent;

    /**
     * @param Core\Model\Layout $layout
     */
    public function __construct(Layout $layout)
    {
        $this->setLayout($layout);

        $this->setBlocks(new ArrayCollection);
        $this->setDependentContent(new \Doctrine\Common\Collections\ArrayCollection());
    }

    /**
     * Adds a block to the page at the specified location
     *
     * @param Block $block
     * @return AbstractPage
     */
    public function addBlock(\Core\Model\Block $block, $location = null, $weight = null)
    {
        $block->page = $this;
        if (null !== $location) {
            $block->location = $location;
        } else {
            if (null === $block->location) {
                throw new \Modo\Model\Exception('Block cannot be added because it does not have a location.');
            }
        }
        if (null !== $weight) {
            $block->weight = $weight;
        } else {
            if (null === $block->weight) {
                throw new \Modo\Model\Exception('Block cannot be added because it does not have a weight.');
            }
        }
        $this->blocks[] = $block;
        return $this;
    }

    /**
     * Adds an array of blocks to the page
     *
     * @param array $blocks
     * @return AbstractPage
     */
    public function addBlocks($blocks)
    {
        foreach ($blocks as $block) {
            $this->addBlock($block);
        }
        return $this;
    }

    /**
     * @param string $title
     * @return AbstractPage
     */
    public function setTitle($title = null)
    {
        if (null !== $title) {
            $lengthValidator = new \Zend_Validate_StringLength(0, 255);
            if (!$lengthValidator->isValid($title)) {
                throw new \Modo\Model\Exception('Title is too long');
            }
        }
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $description
     * @return AbstractPage
     */
    public function setDescription($description = null)
    {
        if (null !== $description) {
            $lengthValidator = new \Zend_Validate_StringLength(0, 500);
            if (!$lengthValidator->isValid($description)) {
                throw new \Modo\Model\Exception('Description is too long');
            }
        }
        $this->description = $description;
        return $this;
    }

    /**
     * @param array $blocks
     * @return AbstractPage
     */
    public function setBlocks($blocks = null)
    {
        if (null !== $blocks) {
            foreach ($blocks AS $block) {
                if (!($block instanceof \Core\Model\Block)) {
                    throw new \Modo\Model\Exception('Block array contains invalid blocks');
                }
            }
            $this->blocks = null;
            foreach($blocks AS $block) {
                $this->addBlock($block);
            }
        } else {
            $this->blocks = null;
        }
        return $this;
    }

    /**
     * @param Layout $layout
     * @return AbstractPage
     */
    public function setLayout(\Core\Model\Layout $layout)
    {
        $this->layout = $layout;
        return $this;
    }

    /**
     * @param Content $content
     * @return AbstractPage
     */
    public function addDependentContent(\Core\Model\Content $content)
    {
        $this->dependentContent[] = $content;
        return $this;
    }

    /**
     * @return string
     */
    public function getResourceId()
    {
        return 'Page.' . $this->getId();
    }

    public function canEdit($role)
    {
        return \Zend_Registry::get('acl')->isAllowed($role, $this, 'edit');
    }
}