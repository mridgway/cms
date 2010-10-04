<?php

namespace Blog\Block\Form;

/**
 * Form block for creating articles
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Block
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity
 */
class Article extends \Core\Model\Block\Dynamic\Form
{
    protected $_blogService;

    public function init()
    {
        if (!$this->_blogService) {
            $this->_blogService = new \Blog\Service\Blog($this->getEntityManager());
        }
        if (!$this->getForm()) {
            $this->setForm($this->_blogService->getAddForm());
        }
    }

    public function process()
    {
        $blogService = new \Blog\Service\Blog($this->getEntityManager());
        $data = $this->getRequest()->getPost();

        $this->_form = $blogService->getAddForm($data);

        if ($this->_form->isValid($data)) {
            unset($data['id']);
            $article = $blogService->createArticle($data);
            return $this->success($article->dependentPage->getURL());
        } else {
            return $this->failure();
        }
    }

    public function configure()
    {
    }
}