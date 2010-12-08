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
     * @var \Taxonomy\Service\Vocabulary
     */
    protected $_vocabularyService;

    /**
     * @param string $termName
     */
    public function getOrCreateTerm($termName, $vocabularySysname)
    {
        try {
            $term = $this->getEntityManager()
                ->getRepository('Taxonomy\Model\Term')
                ->findOneByVocabularySysnameAndName($vocabularySysname, $termName);
        } catch (\Doctrine\ORM\NoResultException $e) {
            $term = $this->createTerm($vocabularySysname, array(
                'name' => $termName
            ));
        }

        return $term;
    }

    /**
     * Find a term.
     *
     * @param integer $id
     * @return \Taxonomy\Model\Term
     */
    public function retrieve($id)
    {
        return $this->getEntityManager()->getRepository('Taxonomy\Model\Term')->find($id);
    }

    /**
     * Creates a new term.
     * 
     * @param \Taxonomy\Model\Vocabulary|string|integer $vocabulary
     * @param array $data
     */
    public function createTerm($vocabulary, $data)
    {
        if (!$vocabulary instanceof \Taxonomy\Model\Vocabulary) {
            $vocabulary = $this->getVocabularyService()->getVocabulary($vocabulary);
        }

        $term = new \Taxonomy\Model\Term($data['name']);
        $term->setVocabulary($vocabulary);

        if (isset($data['definition'])) {
            $term->setDefinition($data['definition']);
        }

        $this->getEntityManager()->persist($term);
        $this->getEntityManager()->flush();

        return $term;
    }

    /**
     * Gets an array of terms.  If the terms do not exist, they are created.
     * 
     * @param array $terms
     * @param string $vocabularyName
     * @return array
     */
    public function getOrCreateTerms(array $terms, $vocabularyName)
    {
        $newTerms = array();
        foreach($terms as $term)
        {
            $newTerms[] = $this->getOrCreateTerm($term, $vocabularyName);
        }

        return $newTerms;
    }

    public function setVocabularyService(\Taxonomy\Service\Vocabulary $vocabularyService)
    {
        $this->_vocabularyService = $vocabularyService;
    }

    public function getVocabularyService()
    {
        return $this->_vocabularyService;
    }
}