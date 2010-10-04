<?php

namespace Blog\Model;

/**
 * Represents a blog article
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity
 * @HasLifecycleCallbacks
 * @Table(name="Blog_Article")
 */
class Article extends \Core\Model\Content
{

    /**
     *
     * @var string
     * @Column(name="blog_title", type="string", length="150")
     */
    protected $title;

    /**
     *
     * @var string
     * @Column(name="blog_content", type="text")
     */
    protected $content;

    public function __construct($title, $content)
    {
        $this->setTitle($title);
        $this->setContent($content);
    }
}