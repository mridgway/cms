<?php

namespace Asset\Service;

/**
 * Service for assets
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class Asset
{

    /**
     * @todo Use mediator instead of this in controller
     *
     * @param array $data
     * @return Asset\Form\Asset
     */
    public function getAddForm($data = null)
    {
        return new \Asset\Form\Asset();
    }

    /**
     * @todo Use mediator instead of this in controller
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