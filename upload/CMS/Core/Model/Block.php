<?php

namespace Core\Model;

use \Core\Model;

/**
 * Represents the position of content on a page
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity(repositoryClass="Core\Repository\Block")
 * @HasLifecycleCallbacks
 * @Table(name="Block")
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="type", type="string")
 * @DiscriminatorMap({"Core\Model\Block\StaticBlock" = "Core\Model\Block\StaticBlock"})
 *
 * @property int $id
 * @property \Core\Model\Page $page
 * @property string $location
 * @property int $weight
 * @property \Core\Model\Module\View $view
 * @property array $configProperties
 * @property array $configValues
 * @property Block $inheritedFrom
 */
abstract class Block
    extends Model\AbstractModel
    implements \Zend_Acl_Resource_Interface
{
    /**
     * @var int
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * The page that this block is associated to
     *
     * @var \Core\Model\AbstractPage
     * @ManyToOne(targetEntity="Core\Model\AbstractPage")
     * @JoinColumn(name="page_id", referencedColumnName="id", nullable="false")
     */
    protected $page;

    /**
     * The location on the page that this is rendered to
     *
     * @var \Core\Model\Layout\Location
     * @ManyToOne(targetEntity="Core\Model\Layout\Location")
     * @JoinColumn(name="location", referencedColumnName="sysname", nullable="false")
     */
    protected $location;

    /**
     * Orders the block in its location
     *
     * @var integer
     * @Column(name="weight", type="integer", nullable="false")
     */
    protected $weight;

    /**
     * The view script that is used to render the contents of this block
     *
     * @var \Core\Model\Module\View
     * @ManyToOne(targetEntity="Core\Model\Module\View")
     * @JoinColumn(name="view_id", referencedColumnName="id", nullable="false")
     */
    protected $view;

    /**
     * The Zend_View instance of our view object
     * This is necessary because blocks that have the same View model need to have separate view
     * instances.
     *
     * @var \Zend_View
     */
    protected $_viewInstance;

    /**
     * Allows setting the configuration data in an array format. If configure() function is set
     * in a block, these settings are ignored.
     *
     * @var array
     */
    protected $configuration = null;

    /**
     * The properties that a block can have
     *
     * @var array
     */
    protected $configProperties;

    /**
     * The values the properties have.  These are pulled from the database while properties are hard coded.
     * 
     * @var array
     * @OneToMany(targetEntity="Core\Model\Block\Config\Value", mappedBy="block", fetch="EAGER", cascade={"update", "persist", "remove"})
     */
    protected $configValues;

    /**
     * @todo cascade updates???
     * @var Block
     * @ManyToOne(targetEntity="Core\Model\Block")
     * @JoinColumn(name="inherit_id", referencedColumnName="id", nullable="true")
     */
    protected $inheritedFrom;

    /**
     *
     * @param Page $page
     * @param string $location
     * @param Module\View $view
     * @param int $weight
     */
    public function __construct(Module\View $view)
    {
        $this->_collectionKeysSet = true; // prevent postload from running
        $this->setView($view);
        $this->setConfigValues(new \Doctrine\Common\Collections\ArrayCollection());

        $this->configure();
    }
    
    /**
     * Renders the contents of the block
     */
    public function render()
    {
        $output = $this->getViewInstance()->render($this->view->getFile());
        return $output;
    }

    public function getView($instance = true)
    {
        if ($instance) {
            return $this->getViewInstance();
        }
        return $this->view;
    }
    
    public function getViewInstance()
    {
        if (null == $this->_viewInstance) {
            $this->_viewInstance = $this->view->getInstance();
        }
        return $this->_viewInstance;
    }

    /**
     * Called by the constructor to set up the config properties. By default this pulls from an
     * array that is set in the block model. The block can override this function if it decides to.
     * @PostLoad
     */
    public function configure()
    {
        if (is_array($this->configuration) && !empty($this->configuration)) {
            $this->configProperties = array();
            foreach ($this->configuration as $name => $values) {
                $property = new Block\Config\Property($name);
                if (isset($values['default'])) {
                    $property->setDefault($values['default']);
                }
                if (isset($values['required'])) {
                    $property->setRequired($values['required']);
                }
                if (isset($values['inheritable'])) {
                    $property->setInheritable($values['inheritable']);
                }
                if (isset($values['inheritableFrom'])) {
                    $property->setInheritableFrom($values['inheritableFrom']);
                }
                $this->addConfigProperty($property);
            }
        } else {
            $this->configProperties = array();
            $this->configValues = new \Doctrine\Common\Collections\ArrayCollection();
        }
    }

    /**
     * Adds a config property. This would probably be called from the configure() function.
     *
     * @param ConfigProperty $property
     * @return Block
     */
    public function addConfigProperty(\Core\Model\Block\Config\Property $property)
    {
        $this->configProperties[$property->name] = $property;
        return $this;
    }

    /**
     * Adds multiple config properties. 
     *
     * @param array $properties
     * @return Block
     */
    public function addConfigProperties(array $properties)
    {
        foreach ($properties as $property) {
            $this->addConfigProperty($property);
        }
        return $this;
    }

    /**
     *
     * @param Value $value
     */
    public function addConfigValue(\Core\Model\Block\Config\Value $value)
    {
        $this->setCollectionKeys();
        $value->setBlock($this);
        $this->configValues[$value->name] = $value;
        return $this;
    }

    /**
     * Sets the value of a config property
     *
     * @param string $name
     * @param string $value
     * @param Core\Model\Block $inheritsFrom
     * @return void
     */
    public function setConfigValue($name, $value, $inheritsFrom = null)
    {
        $this->setCollectionKeys();
        if (!isset($this->configProperties[$name])) {
            throw new \Exception('Config Property does not exist.');
        }

        if (isset($this->configValues[$name])) {
            $this->configValues[$name]->setValue($value);
            $this->configValues[$name]->setInheritsFrom($inheritsFrom);
        } else {
            $configValue = new \Core\Model\Block\Config\Value($name, $value, $inheritsFrom);
            $this->addConfigValue($configValue);
        }
        return $this;
    }

    /**
     *
     * @param string $name
     * @return string
     */
    public function getConfigValue($name)
    {
        $this->setCollectionKeys();
        if (isset($this->configValues[$name])) {
            return $this->configValues[$name]->getValue();
        }

        if (isset($this->configProperties[$name])) {
            return $this->configProperties[$name]->getDefault();
        }

        throw new \Exception('Block with id ' . $this->id . ' does not have ' . $name);
    }

    public function getConfigValues()
    {
        $this->setCollectionKeys();
        return $this->configValues;
    }

    /**
     *
     * @param string $name
     * @return Block\Config\Property
     */
    public function getConfigProperty($name)
    {
        return $this->configProperties[$name];
    }

    /**
     * @todo once I get feedback on collection keys, take out this function
     * unfortunately postload gets called after associations are loaded, so this doesn't work.
     * So... we're going to store a flag and call this when we getConfigValue()... but only once
     * @PostLoad
     */
    private $_collectionKeysSet = false;
    private function setCollectionKeys()
    {
        if ($this->_collectionKeysSet || $this->configValues->isEmpty())
            return;
        
        foreach ($this->configValues as $key => $value) {
            $this->configValues[$value->name] = $value;
            unset($this->configValues[$key]);
        }
        $this->_collectionKeysSet = true;
    }

    /**
     * @param Page $page
     * @return Block
     */
    public function setPage(\Core\Model\AbstractPage $page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @param Location $location
     * @return Block
     */
    public function setLocation(\Core\Model\Layout\Location $location = null)
    {
        if (null === $location && null !== $this->page) {
            throw new \Core\Model\Exception('Cannot null location on a page block.');
        }
        $this->location = $location;
        return $this;
    }

    /**
     * @param integer $weight
     * @return Block
     */
    public function setWeight($weight = null)
    {
        if (null !== $weight) {
            if (!is_numeric($weight)) {
                throw new \Core\Model\Exception('Weight must be a number.');
            }
        } else {
            if (null !== $this->page) {
                throw new \Core\Model\Exception('Cannot null weight on page block.');
            }
        }
        $this->weight = $weight;
        return $this;
    }

    /**
     * @param View $view
     * @return Block
     */
    public function setView(\Core\Model\Module\View $view)
    {
        $this->view = $view;
        $this->getViewInstance();
        return $this;
    }

    /**
     * @param array $properties
     * @return Block
     */
    public function setConfigProperties(array $properties = null)
    {
        if (null !== $properties) {
            foreach ($properties AS $property) {
                if (!($property instanceof \Core\Model\Block\Config\Property)) {
                    throw new \Core\Model\Exception('Property array contains invalid properties.');
                }
            }
            $this->configProperties = new \Doctrine\Common\Collections\ArrayCollection();
            foreach ($properties AS $property) {
                $this->addConfigProperty($property);
            }
        } else {
            $this->configProperties = new \Doctrine\Common\Collections\ArrayCollection();
        }
        return $this;
    }

    /**
     * @param array $values
     * @return Block
     */
    public function setConfigValues($values = null)
    {
        $this->setCollectionKeys();
        if (null !== $values) {
            foreach ($values AS $value) {
                if (!($value instanceof \Core\Model\Block\Config\Value)) {
                    throw new \Core\Model\Exception('Value array contains invalid values.');
                }
            }
            $this->configValues = new \Doctrine\Common\Collections\ArrayCollection();
            foreach ($values AS $value) {
                $this->addConfigValue($value);
            }
        } else {
            $this->configValues = new \Doctrine\Common\Collections\ArrayCollection();
        }
        return $this;
    }

    public function removeConfigValue($key)
    {
        unset($this->configValues[$key]);
    }

    public function removeConfigValues($values = array())
    {
        if (empty($values)) {
            foreach ($this->configValues AS $key => $value) {
                $this->removeConfigValue($key);
            }
        } else {
            foreach ($values AS $key) {
                $this->removeConfigValue($key);
            }
        }
    }

    /**
     * @param array $configuraiton
     * @return Block
     */
    public function setConfiguration(array $configuration = null)
    {
        $this->configuration = $configuration;
        $this->configure();
        return $this;
    }

    /**
     *
     * @param Block $block
     * @return Block
     */
    public function setInheritedFrom(Block $block = null)
    {
        if ($block === $this) {
            throw new \Core\Model\Exception('Block cannot inherit itself.');
        }
        $this->inheritedFrom = $block;
        return $this;
    }

    /**
     * @return string
     */
    public function getResourceId()
    {
        return 'Block.' . $this->getId();
    }


    /**
     * ACTION PERMISSIONS BELOW
     */
    public function canView($role)
    {
        return \Zend_Registry::get('acl')->isAllowed($role, $this, 'view');
    }
    
    /**
     *
     * @return bool
     */
    public function canDelete($role)
    {
        return \Zend_Registry::get('acl')->isAllowed($role, $this, 'delete');
    }


    /**
     *
     * @return bool
     */
    public function canEdit($role)
    {
        return \Zend_Registry::get('acl')->isAllowed($role, $this, 'edit');
    }


    /**
     *
     * @return bool
     */
    public function canConfigure($role)
    {
        return \Zend_Registry::get('acl')->isAllowed($role, $this, 'configure');
    }


    /**
     *
     * @return bool
     */
    public function canMove($role)
    {
        return \Zend_Registry::get('acl')->isAllowed($role, $this, 'move');
    }
}