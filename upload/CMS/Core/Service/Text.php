<?php

namespace Core\Service;

/**
 * Service for text content
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Text extends \Core\Service\AbstractContent
{

    public function retrieve($id)
    {
        return $this->_retrieve($id);
    }

    public function _getDefaultClassName()
    {
        return 'Core\Model\Content\Text';
    }

    /**
     *
     * @param array $data
     * @return Core\Form\Text
     */
    public function getAddForm($data = null)
    {
        return new \Core\Form\Text();
    }

    /**
     *
     * @param Core\Model\Text $route
     * @param array $data
     * @return Core\Form\Text
     */
    public function getEditForm(\Core\Model\Content\Text $text, $data = null)
    {
        $form = new \Core\Form\Text;
        $form->setObject($text);
        if (null !== $data) {
            $form->populate($data);
        }
        return $form;
    }

    /**
     * Find all shared content.
     *
     * @return array
     */
    public function getShared()
    {
        return $this->_em->getRepository('Core\Model\Content\Text')->findSharedText();
    }

    /**
     * Create a new Text object.
     *
     * @param string $title
     * @param string $content
     * @param boolean $shared
     * @return \Core\Model\Content\Text
     */
    public function create($title, $content, $shared = null)
    {
        $this->getEntityManager()->beginTransaction();
        try {
            $text = new \Core\Model\Content\Text($title, $content, $shared);
            $this->getEntityManager()->persist($text);
            $this->getEntityManager()->flush();
            $this->getEntityManager()->commit();
        } catch(\Exception $e) {
            $this->getEntityManager()->rollback();
            $this->getEntityManager()->close();
            throw $e;
        }
        return $text;
    }

    /**
     * Modifies attributes of $text.
     *
     * @param \Core\Model\Content\Text $text
     * @param string $title
     * @param string $content
     */
    public function update($text, $title, $content)
    {
        $text->setTitle($title);
        $text->setContent($content);
        $this->getEntityManager()->flush();
    }

    /**
     * Deletes $text if unshared.
     * 
     * @param \Core\Model\Content\Text $text
     */
    public function delete(\Core\Model\Content\Text $text)
    {
        if(!$text->getShared())
        {
            $this->getEntityManager()->remove($text);
        }
    }
}