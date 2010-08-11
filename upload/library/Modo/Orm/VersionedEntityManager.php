<?php
/**
 * Modo CMS
 */

namespace Modo\Orm;

use Doctrine\Common,
    Doctrine\DBAL,
    Doctrine\ORM;

/**
 * The central manager for entities.  Keeps track of revisions to objects.
 *
 * @category   Page
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: VersionedEntityManager.php 93 2010-01-14 14:51:25Z mike $
 */
class VersionedEntityManager
    extends ORM\EntityManager
    implements Common\EventSubscriber
{
    /**
     * Name of the change model class
     * 
     * @var string
     */
    protected $_changeModelClass = null;

    /**
     * Current revision model
     *
     * @var Model\RevisionableInterface
     */
    protected $_revision = null;
    
    /**
     * Name of the revision model class
     * 
     * @var string
     */
    protected $_revisionModelClass = null;


    /**
     * {@inheritdoc}
     *
     * @param DBAL\Connection     $conn
     * @param string              $name
     * @param ORM\Configuration   $config
     * @param Common\EventManager $eventManager
     * @param string              $revisionClass Name of the revision model class
     * @param string              $changeClass   Name of the change model class
     */
    protected function __construct(DBAL\Connection $conn, ORM\Configuration $config,
        Common\EventManager $eventManager, $revisionClass = null, $changeClass = null)
    {
        parent::__construct($conn, $config, $eventManager);

        if ($revisionClass) {
            $this->setRevisionModelClass($revisionClass);
        }

        if ($changeClass) {
            $this->setChangeModelClass($changeClass);
        }
    }

    /**
     * Add the changes from an entity to the revision
     *
     * Ignore changes if the entity is not versionable.  If revision does not
     * exist, create it.
     *
     * @todo Evaluate whether the before/after check is necessary
     *
     * @param  mixed $entity
     * @return void
     * @throws OrmException If any before/after values are identical
     */
    public function addEntityChangesToRevision($entity)
    {
        $isIdentifiable = !$entity instanceof Model\IdentifiableInterface;
        $isVersionable  = !$entity instanceof Model\VersionableInterface;

        if (!$isIdentifiable || !$isVersionable) {
            return;
        }

        $entityClass   = get_class($entity);
        $entityId      = $entity->getIdentifier();
        $entityChanges = $this->getUnitOfWork()->getEntityChangeSet($entity);
        $revision      = $this->getRevision();

        if (null === $revision) {
            $revision = $this->createRevision();
        }

        foreach($entityChanges as $key => $values) {
            list($before, $after) = $values;

            if ($before === $after) {
                throw new OrmException('The before matches the after in the '
                                     . 'entity change set');
            }

            if ($after instanceof Model\IdentifierInterface) {
                $after = $after->getIdentifier();
            }

            if ($before instanceof Model\IdentifierInterface) {
                $before = $before->getIdentifier();
            }

            $change = $this->createChange($entityClass, $entityId, $key, $after);
            
            $revision->addChange($change);
        }

        $this->setRevision($revision);
    }

    /**
     * Factory for creating a new change entity
     *
     * @param  Model\RevisionableInterface $revision
     * @param  string                      $modelType
     * @param  integer                     $modelId
     * @param  string                      $property
     * @param  mixed                       $value
     * @return Model\ChangeableInterface
     * @throws \Modo\TypeException If change does not implement ChangeableInterface
     */
    public function createChange(Model\RevisionableInterface $revision,
        $modelType, $modelId, $property, $value
    ) {
        $changeClass = $revision->getChangeModelClass();

        $change = new $changeClass();
        if (!$change instanceof Model\ChangeableInterface) {
            throw new \Modo\TypeException("Class '" . get_class($change) . "' "
                                        . "does not implement ChangeableInterface");
        }

        $change->setRevision($revision)
               ->setModelType($modelType)
               ->setModelId($modelId)
               ->setProperty($property)
               ->setValue($value);

        return $change;
    }

    /**
     * Factory for creating a new revision entity
     *
     * @param  integer|null $timestamp
     * @return Model\RevisionableInterface
     * @throws \Modo\TypeException If revision does not implement ChangeableInterface
     */
    public function createRevision($timestamp = null)
    {
        if (null === $timestamp) {
            $timestamp = time();
        }

        $revisionClass = $this->getRevisionModelClass();

        $revision = new $revisionClass();
        if (!$revision instanceof Model\RevisionableInterface) {
            throw new \Modo\TypeException("Class '" . get_class($revision) . "' "
                                        . "does not implement RevisionableInterface");
        }

        $revision->setDate($timestamp);

        return $revision;
    }

    /**
     * {@inheritdoc}
     *
     * In addition, the current revision and all changes associated with it are
     * also flushed to the database.
     *
     * @todo Make this not flush twice.  This would be ideal.
     *
     * @return void
     */
    public function flush()
    {
        $revision = $this->getRevision();

        $this->getConnection()->beginTransaction();
        try {
            parent::flush();

            if (null !== $revision) {
                // persist the revision entity
                $this->persist($revision);

                // persist all of the changes under the current revision
                foreach ($revision->getChanges() as $change) {
                    $this->persist($change);
                    $this->persist($change->getValue());
                }

                parent::flush();
            }
            
            $this->getConnection()->commit();
        } catch (\Exception $e) {
            $this->getConnection()->rollback();
            throw $e;
        }
    }

    /**
     * Get the name of the change model class
     *
     * @return string
     * @throws \Modo\ConfigException If no change class name was configured
     */
    public function getChangeModelClass()
    {
        if (null === $this->_changeModelClass) {
            throw new \Modo\ConfigException('No change model class is configured');
        }

        return $this->_changeModelClass;
    }

    /**
     * Get the revision model
     *
     * @return Model\RevisionableInterface
     */
    public function getRevision()
    {
        return $this->_revision;
    }

    /**
     * Get the class name of the revision model
     *
     * @return string
     * @throws \Modo\ConfigException If no revision class name was configured
     */
    public function getRevisionModelClass()
    {
        if (null === $this->_revisionModelClass) {
            throw new \Modo\ConfigException("No revision model class was configured");
        }

        return $this->_revisionModelClass;
    }

    /**
     * Get an array of function names to use as events to subscribe
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(ORM\Events::postUpdate, ORM\Events::postPersist);
    }

    /**
     * Post persist event handler
     *
     * @param  LifecycleEventArgs $eventArgs
     * @return void
     */
    public function postPersist(ORM\Event\LifecycleEventArgs $eventArgs)
    {
        $this->addEntityChangesToRevision($eventArgs->getEntity());
    }

    /**
     * Post update event handler
     *
     * @param  LifecycleEventArgs $eventArgs
     * @return void
     */
    public function postUpdate(ORM\Event\LifecycleEventArgs $eventArgs)
    {
        $this->addEntityChangesToRevision($eventArgs->getEntity());
    }

    /**
     * Set the name of the change model class
     *
     * @param  string $class
     * @return VersionedEntityManager *Provides fluid interface*
     */
    public function setChangeModelClass($class)
    {
        $this->_changeModelClass = (string)$class;

        return $this;
    }

    /**
     * Set the current revision
     *
     * @param  Model\RevisionableInterface $revision
     * @return VersionedEntityManager *Provides fluid interface*
     */
    public function setRevision(Model\RevisionableInterface $revision)
    {
        $this->_revision = $revision;

        return $this;
    }

    /**
     * Set the name of the revision model class
     *
     * @param  string $class
     * @return VersionedEntityManager *Provides fluid interface*
     */
    public function setRevisionModelClass($class)
    {
        $this->_revisionModelClass = (string)$class;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param  mixed               $conn          An array with the connection
     *                                            parameters or an existing
     *                                            Connection instance
     * @param  ORM\Configuration   $config        The Configuration to use
     * @param  Common\EventManager $eventManager  The EventManager to use
     * @param  string              $revisionClass Name of the revision model class
     * @param  string              $changeClass   Name of the change model class
     * @return VersionedEntityManager The created EntityManager
     */
    public static function create($conn, ORM\Configuration $config = null,
        Common\EventManager $eventManager = null,
        $revisionClass = null, $changeClass = null
    ) {
        $config = $config ?: new ORM\Configuration();

        if (is_array($conn)) {
            $conn = DBAL\DriverManager::getConnection($conn, $config, ($eventManager ?: new Common\EventManager()));
        } else if ($conn instanceof DBAL\Connection) {
            if ($eventManager !== null && $conn->getEventManager() !== $eventManager) {
                 throw Common\DoctrineException::invalidEventManager('Cannot use different EventManagers for EntityManager and Connection.');
            }
        } else {
            throw Common\DoctrineException::invalidParameter($conn);
        }

        return new VersionedEntityManager($conn, $config,
                                          $conn->getEventManager(),
                                          $revisionClass, $changeClass);
    }
}