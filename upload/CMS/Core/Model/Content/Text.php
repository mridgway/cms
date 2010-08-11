<?php
/**
 * Modo CMS
 */

namespace Core\Model\Content;

use \Modo\Orm\Model;

/**
 * Textual content that has been created via a WYSIWYG editor
 *
 * @category   Model
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Text.php 297 2010-05-12 13:34:56Z mike $
 *
 * @Entity
 * @property int $id
 * @property string $title
 * @property string $content
 * @property bool $shared
 */
class Text extends \Core\Model\Content implements Model\VersionableInterface
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
            throw new \Modo\Model\Exception('Title must be less than 100 characters.');
        }
        $this->title = $title;
        return $this;
    }

    public function setContent($content)
    {
        $validator = new \Zend_Validate_StringLength(0, 65000);
        if (!$validator->isValid($content)) {
            throw new \Modo\Model\Exception('Content must be less than 65000 characters.');
        }
        $this->content = $content;
        return $this;
    }

    public function setShared($shared = false)
    {
        if (!is_bool($shared)) {
            throw new \Modo\Model\Exception('Shared must be boolean.');
        }
        $this->shared = $shared;
        return $this;
    }
}