<?php
/**
 * Modo CMS
 */

namespace Core\Model\Revision;

use Modo\Orm\Model;

/**
 * An individual change to an object's property
 *
 * @see \Core\Model\Revision
 *
 * @category   Revision
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Change.php 297 2010-05-12 13:34:56Z mike $
 *
 * @Entity
 * @Table(name="Revision_Change")
 *
 * @property int $id
 * @property \Core\Revision $revision
 * @property string $modelType
 * @property int $modelId
 * @property string $property
 * @property \Core\Revision\Value $value
 */
class Change implements Model\ChangeableInterface
{
    /**
     * @Id
     * @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /*
     * @ManyToOne(targetEntity="Core\Revision")
     * @JoinColumn(name="revision_id", referencedColumnName="id")
     */
    protected $revision;

    /**
     * @Column(name="model_type", type="string", length="100")
     */
    protected $modelType;

    /**
     * @Column(name="model_id", type="integer")
     */
    protected $modelId;

    /**
     * @Column(name="property", name="`property`", type="string", length="100")
     */
    protected $property;

    /**
     * @OneToOne(targetEntity="Core\Model\Revision\Value", cascade={"all"})
     * @JoinColumn(name="value_id", referencedColumnName="id")
     */
    public $value;


    /**
     * Prefix for the revision value class
     *
     * @var string|null
     */
    protected $_revisionValueClassPrefix = null;


    /**
     * Constructor
     *
     * Initialize the change information
     *
     * @param Model\RevisionableInterface $revision
     * @param string                      $modelType
     * @param integer                     $modelId
     * @param string                      $property
     * @param mixed                       $value
     */
    public function __construct(Model\RevisionableInterface $revision = null,
            $modelType = null, $modelId = null, $property = null, $value = null)
    {
        if ($revision) {
            $this->setRevision($revision);
        }

        if ($modelType) {
            $this->setModelType($modelType);
        }

        if ($modelId) {
            $this->setModelId($modelId);
        }

        if ($property) {
            $this->setProperty($property);
        }

        if ($value) {
            $this->setValue($value);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return integer
     */
    public function getModelId()
    {
        return $this->modelId;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getModelType()
    {
        return $this->modelType;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * {@inheritdoc}
     *
     * @return RevisionableInterface
     */
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     * Get the prefix for the revision value class
     *
     * @return string
     */
    public function getRevisionValueClassPrefix()
    {
        if (null === $this->_revisionValueClassPrefix) {
            $this->setRevisionValueClassPrefix('\Core\Model\Revision\Value\\');
        }

        return $this->_revisionValueClassPrefix;
    }

    /**
     * {@inheritdoc}
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     *
     * @param  integer $id
     * @return Change *Provides fluid interface*
     */
    public function setModelId($id)
    {
        $this->modelId = (int)$id;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param  string $type
     * @return Change *Provides fluid interface*
     */
    public function setModelType($type)
    {
        $this->modelType = (string)$type;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param  string $property
     * @return Change *Provides fluid interface*
     */
    public function setProperty($property)
    {
        $this->property = (string)$property;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param  Model\RevisionableInterface $revision
     * @return Change *Provides fluid interface*
     */
    public function setRevision(Model\RevisionableInterface $revision)
    {
        $this->revision = $revision;

        return $this;
    }

    /**
     * Set the prefix for the revision value class
     *
     * @param  string $prefix
     * @return Change *Provides fluid interface*
     */
    public function setRevisionValueClassPrefix($prefix)
    {
        $this->_revisionValueClassPrefix = (string)$prefix;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param  mixed $value
     * @return Change *Provides fluid interface*
     */
    public function setValue($value)
    {
        if (is_numeric($value)) {
            $valueType = 'Integer';
        } else if (strlen($value) <= 5000) {
            $valueType = 'String';
        } else {
            $valueType = 'Text';
        }

        $valueClass = $this->getRevisionValueClassPrefix() . $valueType;
        $this->value = new $valueClass($value);

        return $this;
    }
}