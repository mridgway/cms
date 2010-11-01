<?php

namespace Blog\Service;

/**
 * Service for blog articles
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Blog extends \Core\Service\AbstractService
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
        $placeholders = array(
            self::ARTICLE_SYSNAME => array(
                'content' => $article,
                'view' => \Core\Module\Registry::getInstance()
                            ->getDatabaseStorage()
                            ->getModule('Blog')
                            ->getContentType('BlogArticle')
                            ->getView('default')
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