<?php
/**
 * Modo CMS
 */

namespace Core\Model;

use Modo\Orm\Model;

/**
 * Keeps an array of Changes
 * @see \Core\Revision\Changes
 *
 * @category   Revision
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Revision.php 297 2010-05-12 13:34:56Z mike $
 * 
 * @Entity
 * @property int $id
 * @property integer $user
 * @property integer $date
 * @property string $message
 * @property \Core\Model\Revision\Change $changes
 */
class Revision implements Model\RevisionableInterface
{
    /**
     * @Id
     * @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
    */
    public $id;

    /**
     * @Column(name="user", type="integer", nullable="TRUE")
     */
    public $user;

    /**
     * @Column(name="date", type="integer", nullable="TRUE")
     */
    public $date;

    /**
     * @Column(name="message", type="string", length="500", nullable="TRUE")
     */
    public $message;

    /**
     * @OneToMany(targetEntity="Core\Model\Revision\Change", mappedBy="revision", cascade={"all"})
     */
    public $changes;


    /**
     * Name of the change model class
     *
     * @var string|null
     */
    protected $_changeModelClass = null;

    
    /**
     * Constructor
     *
     * Initialize the changes collection and save optional revision information
     *
     * @param integer|null $when
     * @param integer|null $who
     * @param string|null  $message
     * @param string       $collectionClass The fully qualified class name for
     *                                      the change collection
     */
    public function __construct($when = null, $who = null, $message = '',
            $collectionClass = '\Doctrine\Common\Collections\ArrayCollection')
    {
        $this->user    = $who;
        $this->date    = $when;
        $this->message = $message;

        $this->changes = new $collectionClass();
    }

    /**
     * {@inheritdoc}
     *
     * @param Model\ChangeableInterface $change
     */
    public function addChange(Model\ChangeableInterface $change)
    {
        $this->changes[] = $change;

        return $this;
    }

    /**
     * Create a new change and add it to the revision
     *
     * @param  string  $modelType
     * @param  integer $modelId
     * @param  string  $property
     * @param  mixed   $value
     * @return Revision
     */
    public function createChange($modelType, $modelId, $property, $value)
    {
        $changeClass = $this->getChangeModelClass();

        $change = new $changeClass();
        $change->setRevision($this)
               ->setModelType($modelType)
               ->setModelId($modelId)
               ->setProperty($property)
               ->setValue($value);

        $this->addChange($change);

        return $this;
    }

    /**
     * Get the name of the change model class
     *
     * @return string
     */
    public function getChangeModelClass()
    {
        if (null === $this->_changeModelClass) {
            $this->setChangeModelClass('\Core\Model\Revision\Change');
        }

        return $this->_changeModelClass;
    }

    /**
     * Get the changes from the revision
     *
     * @return mixed
     */
    public function getChanges()
    {
        return $this->changes;
    }

    /**
     * Set the name of the change model class
     *
     * @param  string $class
     * @return Revision *Provides fluid interface*
     */
    public function setChangeModelClass($class)
    {
        $this->_changeModelClass = (string)$class;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param  integer|string $date
     * @return Revision *Provides fluid interface*
     */
    public function setDate($date)
    {
        $timestamp = $date;

        if (!is_int($date)) {
            $date = (string)$date;
            $timestamp = strtotime($date);

            if (false === $timestamp) {
                throw new \InvalidArgumentException("'$date' can not be converted to a valid unix timestamp");
            }
        }

        $this->date = $timestamp;
        
        return $this;
    }
}