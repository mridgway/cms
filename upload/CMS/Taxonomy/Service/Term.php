<?php

namespace Taxonomy\Service;

/**
 * Service for taxonomy terms
 *
 * @package     CMS
 * @subpackage  Taxonomy
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Term extends \Core\Service\AbstractService
{
    /**
     * @param string $termName
     */
    public function getOrCreateTerm($termName, $vocabularySysname)
    {
        $term = $this->getEntityManager()
                ->getRepository('Taxonomy\Model\Term')
                ->findByVocabularySysnameAndName($vocabularySysname, $termName);
        if (!$term) {
            $term = $this->createTerm(array(
                'name' => $termName
            ));
        }

        return $term;
    }

    /**
     * @param \Taxonomy\Model\Vocabulary|string|integer $vocabulary
     * @param array $data
     */
    public function createTerm($vocabulary, $data)
    {
        if (!$vocabulary instanceof \Taxonomy\Model\Vocabulary) {
            $repository = $this->getEntityManager()->getRepository('Taxonomy\Model\Vocabulary');
            if (is_int($vocabulary)) {
                $vocabulary = $repository->find($vocabulary);
            } else {
                $vocabulary = $repository->findBySysname($vocabulary);
            }
        }

        $term = new \Taxonomy\Model\Term($data['name']);
        $term->setVocabulary($vocabulary);

        if (isset($data['definition'])) {
            $term->setDefinition($data['definition']);
        }

        return $term;
    }
}