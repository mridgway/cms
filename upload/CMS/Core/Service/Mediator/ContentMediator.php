<?php

namespace Core\Service\Mediator;

/**
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class ContentMediator extends AbstractMediator
{
    public function init()
    {
        $em = $this->getEntityManager();
        $auth = $this->getAuth();
        $this->setFields(
            array(
                'id' => array(
                    'setMethod' => false
                ),
                'author' => array(
                    'getMethod' => function ($instance) {
                        return $instance->getAuthor() ? $instance->getAuthor() : null;
                    },
                    'setMethod' => function ($instance, $value) use ($em) {
                        if ($value) {
                            $value = $em->getRepository('User\Model\User')->find($value);
                        }
                        if (!$value) {
                            $value = null;
                        }
                        $instance->setAuthor($value);
                    }
                ),
                'authorName' => array(
                ),
                'creationDate' => array(
                    'getMethod' => function ($instance) {
                        return $instance->getCreationDate()->format('m-d-Y');
                    },
                    'filterMethod' => function ($instance, $value) {
                        return $value ? new \DateTime($value) : null;
                    }
                ),
                'modificationDate' => array(
                    'getMethod' => function ($instance) {
                        return $instance->getCreationDate()->format('m-d-Y');
                    },
                    'filterMethod' => function ($instance, $value) {
                        return $value ? new \DateTime($value) : null;
                    }
                ),
                'tags' => array(
                    'getMethod' => function ($instance) {
                        $tags = array();
                        foreach ($instance->getTags() AS $tag) {
                            $tags[] = $tag->name;
                        }
                    }
                )
            )
        );
    }
}