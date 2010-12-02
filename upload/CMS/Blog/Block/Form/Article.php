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
    public function init()
    {
        if (!$this->getForm()) {
            $this->setForm($this->getServiceContainer()->getService('blogService')->getAddForm());
        }
    }

    public function process()
    {
        $blogService = $this->getServiceContainer()->getService('blogService');
        $data = $this->getRequest()->getPost();

        $this->setForm($blogService->getAddForm($data));

        if ($this->getForm()->isValid($data)) {
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