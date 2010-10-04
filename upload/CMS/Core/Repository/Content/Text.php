<?php

namespace Core\Repository\Content;

/**
 * Repository for the text content type
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Repository
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Text extends \Doctrine\ORM\EntityRepository
{
    /**
     * Finds all text content that is shared
     *
     * @return array
     */
    public function findSharedText()
    {
        $qb = $this->createQueryBuilder('t');
        $qb->where('t.shared = 1');
        return $qb->getQuery()->getResult();
    }
}