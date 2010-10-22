<?php

namespace Core\Service;

/**
 * Service for routes
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
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

    public function create($routeName)
    {
        return new \Core\Model\Route($routeName);
    }

    public function edit($route, $template)
    {
        $route->setTemplate($template);
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