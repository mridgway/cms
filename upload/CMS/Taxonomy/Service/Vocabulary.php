<?php

namespace Taxonomy\Service;

/**
 * Service for taxonomy vocabularies
 *
 * @package     CMS
 * @subpackage  Taxonomy
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Vocabulary extends \Core\Service\AbstractService
{
    /**
     * Create a vocabulary.
     *
     * @param string $name
     * @param string $sysname
     * @param string $description
     * @return \Taxonomy\Model\Vocabulary
     */
    public function create($name, $sysname, $description)
    {
        $vocabulary = new \Taxonomy\Model\Vocabulary($name, $sysname, $description);
        $this->_em->persist($vocabulary);
        $this->_em->flush();

        return $vocabulary;
    }
}