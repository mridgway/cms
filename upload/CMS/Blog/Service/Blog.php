<?php
/**
 * Modo CMS
 */

namespace Blog\Service;

/**
 * Service for blog articles
 *
 * @category   Service
 * @package    Blog
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Blog.php 297 2010-05-12 13:34:56Z mike $
 */
class Blog extends \Modo\Service\AbstractService
{

    const ARTICLE_SYSNAME = 'blogArticle';

    public function createArticle($data)
    {
        //create article
        $article = new \Blog\Model\Article($data['title'], $data['content']);
        
        //get template
        $template = $this->getArticleTemplate();
        
        //create page from template
        $pageService = new \Core\Service\Page($this->getEntityManager());
        $this->_em->getReference('Core\Model\View', 5);
        $this->_em->getRepository('Core\Model\View')->getView('Blog', 'Article', 'default');
        $placeholders = array(
            self::ARTICLE_SYSNAME => array(
                'content' => $article,
                'view' => $this->_em->getRepository('Core\Model\View')->getView('Blog', 'Article', 'default')
                )
            );
        $page = $pageService->createPageFromTemplate($template, $placeholders);
        $this->getEntityManager()->persist($page);

        $this->getEntityManager()->persist($article);
        $this->getEntityManager()->flush();

        //get route
        $route = $this->getArticleRoute();

        //route to page
        $pageRoute = $route->routeTo($page, array('id' => $article->id));
        $this->getEntityManager()->persist($pageRoute);
        $article->dependentPage = $page;
        $page->dependentContent[] = $article;
        $this->getEntityManager()->flush();

        return $article;
    }

    /**
     *
     * @param array $data
     * @return Blog\Form\Article
     */
    public function getAddForm($data = null)
    {
        return new \Blog\Form\Article();
    }

    /**
     *
     * @param Blog\Model\Article $route
     * @param array $data
     * @return Blog\Form\Article
     */
    public function getEditForm(\Blog\Model\Article $article, $data = null)
    {
        $form = new \Blog\Form\Article;
        $form->setObject($article);
        if (null !== $data) {
            $form->populate($data);
        }
        return $form;
    }

    public function getArticleTemplate()
    {
        return $this->getEntityManager()
                ->getRepository('Core\Model\Template')
                ->findOneBySysname(self::ARTICLE_SYSNAME);
    }

    public function getArticleRoute()
    {
        return $this->getEntityManager()
                ->getRepository('Core\Model\Route')
                ->findOneBySysname(self::ARTICLE_SYSNAME);
    }
}