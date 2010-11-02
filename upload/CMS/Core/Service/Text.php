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
class Text extends \Core\Service\AbstractService
{

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
    public function create($title, $content, $shared = false)
    {
        return new \Core\Model\Content\Text($title, $content, $shared);
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