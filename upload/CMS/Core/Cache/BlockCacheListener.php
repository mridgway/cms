<?php

namespace Core\Cache;

class BlockCacheListener
{
    protected $_tags = array();
    protected $_clearedTags = array();
    
    public function onFlush(\Doctrine\ORM\Event\OnFlushEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() AS $entity) {
            $this->findTags($entity);
        }

        foreach ($uow->getScheduledEntityUpdates() AS $entity) {
            $this->findTags($entity);
        }

        foreach ($uow->getScheduledEntityDeletions() AS $entity) {
            $this->findTags($entity);
        }

        foreach ($uow->getScheduledCollectionDeletions() AS $col) {

        }

        foreach ($uow->getScheduledCollectionUpdates() AS $col) {

        }

        $this->clearCache();
    }

    public function clearCache()
    {
        $blockCache = \Zend_Registry::get('serviceContainer')->getService('blockCache');
        $tagsToClear = \array_diff($this->getTags(), $this->_clearedTags);
        $blockCache->clean(\Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, $tagsToClear);
        $this->_clearedTags = $this->getTags();
    }

    private function findTags($entity)
    {
        $class = \get_class($entity);
        $classNameArray = explode('\\', $class);
        $this->addTag($classNameArray[0]);
    }

    public function getTags()
    {
        return $this->_tags;
    }

    public function setTags($tags)
    {
        $this->_tags = $tags;
    }

    public function addTag($tag)
    {
        if(\is_string($tag) && !\in_array($tag, $this->getTags())) {
            $this->_tags[] = $tag;
        }
    }

    public function reset()
    {
        $this->_tags = array();
    }
}