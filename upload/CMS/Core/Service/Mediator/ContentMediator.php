<?php

namespace Core\Service\Mediator;

/**
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class ContentMediator extends \Core\Service\AbstractMediator
{

    /**
     * @var \Taxonomy\Service\Term
     */
    protected $_termService;

    /**
     * @var \User\Service\User
     */
    protected $_userService;

    /**
     * @var \Asset\Service\Asset
     */
    protected $_assetService;

    public function init()
    {
        $self = $this;
        $this->setFields(
            array(
                'id' => array(
                    'setMethod' => false
                ),
                'author' => array(
                    'getMethod' => function ($instance) {
                        return $instance->getAuthor() ? 
                                $instance->getAuthor()->getId()
                                : null;
                    },
                    'setMethod' => function ($instance, $value) use ($self) {
                        if (null !== $value) {
                            $value = $self->getUserService()->getUser($value);
                        }
                        $instance->setAuthor($value);
                    }
                ),
                'authorName' => array(),
                'creationDate' => array(
                    'getMethod' => function ($instance) {
                        return $instance->getCreationDate() ? $instance->getCreationDate()->format('m-d-Y') : null;
                    },
                    'filterMethod' => function ($instance, $value) {
                        try {
                            return $value ? \DateTime::createFromFormat('m-d-Y', $value) : new \DateTime();
                        } catch (\Exception $e) {
                            throw new \Exception(sprintf('Invalid date passed: %s', $value));
                        }
                    }
                ),
                'modificationDate' => array(
                    'getMethod' => function ($instance) {
                        return $instance->getModificationDate() ? $instance->getModificationDate()->format('m-d-Y') : null;
                    },
                    'filterMethod' => function ($instance, $value) {
                        try {
                            return $value ? \DateTime::createFromFormat('m-d-Y', $value) : new \DateTime();
                        } catch (\Exception $e) {
                            throw new \Exception(sprintf('Invalid date passed: %s', $value));
                        }
                    }
                ),
                'tags' => array(
                    'getMethod' => function ($instance) {
                        $tags = array();
                        foreach ($instance->getTags() AS $tag) {
                            $tags[] = $tag->getName();
                        }
                        return $tags;
                    },
                    'setMethod' => function ($instance, $values) use ($self) {
                        $tags = array();
                        if ($values) {
                            foreach ($values AS $tagName) {
                                $tags[] = $self->getTermService()->getOrCreateTerm($tagName, 'contentTags');
                            }
                        }
                        $instance->setTags($tags);
                    }
                ),
                'isFeatured' => array()
            )
        );
    }

    /**
     * @param \User\Service\User $userService
     */
    public function setUserService(\User\Service\User $userService)
    {
        $this->_userService = $userService;
    }

    /**
     * @return User\Service\User
     */
    public function getUserService()
    {
        return $this->_userService;
    }

    /**
     * @param \Taxonomy\Service\Term $termService
     */
    public function setTermService(\Taxonomy\Service\Term $termService)
    {
        $this->_termService = $termService;
    }

    /**
     * @return \Taxonomy\Service\Term
     */
    public function getTermService()
    {
        return $this->_termService;
    }

    /**
     * @param \Asset\Service\Asset $assetService
     */
    public function setAssetService(\Asset\Service\Asset $assetService)
    {
        $this->_assetService = $assetService;
    }

    /**
     * @return \Asset\Service\Asset
     */
    public function getAssetService()
    {
        return $this->_assetService;
    }
}