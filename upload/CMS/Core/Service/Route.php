<?php
/**
 * Modo CMS
 */

namespace Core\Service;

/**
 * Service for Routes
 *
 * @category   Route
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Route.php 297 2010-05-12 13:34:56Z mike $
 */
class Route extends \Core\Service\AbstractService
{

    /**
     * Returns a form for adding a route. Takes in a dataset to prepopulate the fields.
     *
     * @param array $data
     * @return Route
     */
    public function getAddForm($data = null)
    {
        return new \Core\Form\Route;
    }

    /**
     * Returns a form for editing a route. Takes in a route to prepopulate the fields and a dataset
     * to override the current values.
     *
     * @param Route $route
     * @param array $data
     * @return \Core\Form\Route
     */
    public function getEditForm(\Core\Model\Route $route, $data = null)
    {
        $form = new \Core\Form\Route;
        $form->setObject($route);
        if (null !== $data) {
            $form->populate($data);
        }
        return $form;
    }
}