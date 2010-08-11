<?php
/**
 * Modo CMS
 */

namespace Core\Service;

/**
 * Service for textual content
 *
 * @category   Service
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Text.php 297 2010-05-12 13:34:56Z mike $
 */
class Text
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
}