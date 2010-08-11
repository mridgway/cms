<?php
/**
 * Modo CMS
 */

namespace Asset\Service;

/**
 * Service for textual content
 *
 * @category   Service
 * @package    Asset
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Asset.php 297 2010-05-12 13:34:56Z mike $
 */
class Asset
{

    /**
     *
     * @param array $data
     * @return Asset\Form\Asset
     */
    public function getAddForm($data = null)
    {
        return new \Asset\Form\Asset();
    }

    /**
     *
     * @param Asset\Model\Asset $asset
     * @param array $data
     * @return Asset\Form\Asset
     */
    public function getEditForm(\Asset\Model\Asset $asset, $data = null)
    {
        $form = new \Asset\Form\Asset;
        $form->setObject($asset);
        if (null !== $data) {
            $form->populate($data);
        }
        return $form;
    }
}