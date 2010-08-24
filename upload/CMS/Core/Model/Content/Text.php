<?php

namespace Core\Model\Content;

use \Core\Model;

/**
 * Textual content that can been created via a WYSIWYG editor
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 *
 * @Entity
 * @property int $id
 * @property string $title
 * @property string $content
 * @property bool $shared
 */
class Text extends \Core\Model\Content
{
    /**
     * @var string
     * @Column(name="title", type="string", length="100", nullable="false")
     */
    protected $title;

    /**
     * @var string
     * @Column(name="content", type="text", nullable="false")
     */
    protected $content;

    /**
     * @var boolean
     * @Column(name="shared", type="boolean", nullable="true")
     */
    protected $shared;

    public function __construct($title, $content, $shared = false)
    {
        $this->setTitle($title);
        $this->setContent($content);
        $this->setShared($shared);
    }

    public function setTitle($title)
    {
        $validator = new \Zend_Validate_StringLength(0, 100);
        if (!$validator->isValid($title)) {
            throw new \Core\Model\Exception('Title must be less than 100 characters.');
        }
        $this->title = $title;
        return $this;
    }

    public function setContent($content)
    {
        $validator = new \Zend_Validate_StringLength(0, 65000);
        if (!$validator->isValid($content)) {
            throw new \Core\Model\Exception('Content must be less than 65000 characters.');
        }
        $this->content = $content;
        return $this;
    }

    public function setShared($shared = false)
    {
        if (!is_bool($shared)) {
            throw new \Core\Model\Exception('Shared must be boolean.');
        }
        $this->shared = $shared;
        return $this;
    }
}