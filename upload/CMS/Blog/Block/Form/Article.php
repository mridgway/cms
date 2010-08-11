<?php
/**
 * Modo CMS
 */
namespace Blog\Block\Form;

/**
 * A test block
 *
 * @category   Model
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Article.php 297 2010-05-12 13:34:56Z mike $
 *
 * @Entity
 */
class Article extends \Core\Model\Block\Dynamic\Form\AbstractForm
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