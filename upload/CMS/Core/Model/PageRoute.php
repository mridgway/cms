<?php

namespace Core\Model;

use \Core\Model;

/**
 * Links a Route to a specific page via parameters
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity(repositoryClass="Core\Repository\PageRoute")
 * @Table(name="pageroute")
 * @HasLifecycleCallbacks
 *
 * @property int $id
 * @property \Core\Model\Route $route
 * @property \Core\Model\RouteParam[] $params
 * @property \Core\Model\Page $page
 */
class PageRoute extends Model\AbstractModel
{
    /**
     * @var integer
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *
     * @var Route
     * @ManyToOne(targetEntity="Core\Model\Route", inversedBy="pageRoutes")
     * @JoinColumn(name="route_id", referencedColumnName="id", nullable="false")
     */
    protected $route;

    /**
     *
     * @var array
     * @Column(name="params", type="array", nullable="true")
     */
    protected $params;

    /**
     *
     * @var Page
     * @OneToOne(targetEntity="Core\Model\Page", inversedBy="pageRoute")
     */
    protected $page;

    /**
     * @var PageRoute
     * @Column(name="redirect", type="boolean", nullable="false")
     */
    protected $redirect;

    /**
     * @param Route $route
     * @param Page $page
     */
    public function __construct(Route $route, Page $page, $params = array())
    {
        $this->setRoute($route);
        $this->setPage($page);
        $this->setParams($params);
        $this->redirect = false;
    }

    /**
     * Sets a route parameter
     *
     * @param string $name
     * @param string $value
     */
    public function setParam($name, $value)
    {
        if (false === array_search($name, $this->route->getVariables())) {
            throw new \Core\Model\Exception('Variable `' . $name . '` does not exist in route.');
        }
        if (is_numeric($value)) {
            $this->params[$name] = (int) $value;
        } else {
            $this->params[$name] = $value;
        }
        return $this;
    }

    /**
     *
     * @param string $name
     * @return mixed
     */
    public function getParam($name)
    {
        return $this->params[$name];
    }

    /**
     * Returns the URL for the route
     *
     * @return string
     */
    public function getURL()
    {
        $params = $this->params;
        $relative = $this->route->assemble($params);
        if (!$relative) {
            return '/';
        }
        return '/' . $relative . '/';
    }

    /**
     * @param Route $route
     * @return PageRoute
     */
    public function setRoute(Route $route)
    {
        $this->route = $route;
        return $this;
    }

    /**
     *
     * @param array $params
     * @return PageRoute
     */
    public function setParams(array $params = array())
    {
        if (!empty($params)) {
            foreach ($params AS $key => $value) {
                $this->setParam($key, $value);
            }
        } else {
            $this->params = $params;
        }
        return $this;
    }

    /**
     *
     * @param bool $redirect
     */
    public function setRedirect($redirect = false)
    {
        if (!is_bool($redirect)) {
            throw new \Core\Model\Exception('Redirect must be a boolean value');
        }
        $this->redirect = $redirect;
        return $this;
    }

    /**
     *
     * @param Page $page
     * @return PageRoute
     */
    public function setPage(\Core\Model\Page $page)
    {
        if (null === $page->getPageRoute()) {
            $page->setPageRoute($this);
        } else if ($page->getPageRoute() != $this) {
            $this->setRedirect(true);
        }
        $this->page = $page;
        return $this;
    }
}