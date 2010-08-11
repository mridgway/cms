<?php
/**
 * Modo CMS
 */

namespace Core\Model;

use \Modo\Orm\Model;

/**
 * Links a Route to a specific page via parameters
 *
 * @category   PageRoute
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: PageRoute.php 297 2010-05-12 13:34:56Z mike $
 *
 * @Entity(repositoryClass="Core\Repository\PageRoute")
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
     * @ManyToOne(targetEntity="Core\Model\Route")
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
     * @ManyToOne(targetEntity="Core\Model\Page")
     * @JoinColumn(name="page_id", referencedColumnName="id", nullable="false")
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
            throw new \Modo\Model\Exception('Variable `' . $name . '` does not exist in route.');
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
        if (!is_array($params)) {
            $params = $this->decodeObject($params);
        }
        return '/' . $this->route->assemble($params) . '/';
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
            throw new \Modo\Model\Exception('Redirect must be a boolean value');
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
        if (null === $page->getPrimaryPageRoute()) {
            $page->setPrimaryPageRoute($this);
        } else if ($page->getPrimaryPageRoute() != $this) {
            $this->setRedirect(true);
        }
        $this->page = $page;
        return $this;
    }
}