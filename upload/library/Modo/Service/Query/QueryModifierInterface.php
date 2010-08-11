<?php
/**
 * Modo CMS
 */

namespace Modo\Service\Query;

/**
 * Description of QueryModifierInterface
 *
 * @category   Modo
 * @package    Module
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: QueryModifierInterface.php 88 2010-01-13 18:15:08Z court $
 */
interface QueryModifierInterface
{
    /**
     * Apply appropriate DQL logic given currnet parameters
     *
     * @param  \Doctrine\Orm\QueryBuilder $qb
     * @return \Doctrine\Orm\QueryBuilder
     */
    public function applyToQueryBuilder(\Doctrine\Orm\QueryBuilder $qb);

    /**
     * Get the hydration mode necessary for the query
     *
     * @return integer
     */
    public function getHydrationMode();

    /**
     * Get whether the constrained query should return a collection of results.
     *
     * If set to true, a collection (array) of results should be returned.
     * If set to false, an individual entity should be returned.
     *
     * @return boolean
     */
    public function getReturnCollection();

    /**
     * Set the hydration mode necessary for the query
     *
     * @param integer $hydrationMode
     */
    public function setHydrationMode($hydrationMode);

    /**
     * Set whether the constrained query should return a collection of results.
     *
     * If set to true, a collection (array) of results should be returned.
     * If set to false, an individual entity should be returned.
     *
     * @param boolean $flag
     */
    public function setReturnCollection($flag);
}