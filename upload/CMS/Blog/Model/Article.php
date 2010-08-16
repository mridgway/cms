<?php
/**
 * Modo CMS
 */

namespace Blog\Model;

/**
 * Description of Article
 *
 * @category   Model
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Article.php 297 2010-05-12 13:34:56Z mike $
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