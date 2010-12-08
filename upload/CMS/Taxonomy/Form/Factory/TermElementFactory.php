<?php

namespace Taxonomy\Form\Factory;

/**
 * A term and definition
 *
 * @package     CMS
 * @subpackage  Taxonomy
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class TermElementFactory
{
    public static function termMultiTagElement($vocabularyName)
    {
        $vocabulary = \Zend_Registry::get('doctrine')
            ->getRepository('Taxonomy\Model\Vocabulary')
            ->findOneBySysname($vocabularyName);

        $options = array();
        foreach ($vocabulary->getTerms() AS $term) {
            $options[] = $term->getName();
        }

        $element = new \Taxonomy\Form\Element\MultiTag('tags');
        $element->setJQueryParam('source', $options);

        return $element;
    }
    
    public static function termAutocompleteElement($vocabularyName)
    {
        $vocabulary = \Zend_Registry::get('doctrine')
            ->getRepository('Taxonomy\Model\Vocabulary')
            ->findOneBySysname($vocabularyName);

        $options = array();
        foreach ($vocabulary->getTerms() AS $term) {
            $options[] = $term->getName();
        }

        $element = new \Core\Form\Element\Autocomplete('term');
        $element->setJQueryParam('source', $options);

        return $element;
    }

    public static function termSelectElement($vocabularyName)
    {
        $vocabulary = \Zend_Registry::get('doctrine')
            ->getRepository('Taxonomy\Model\Vocabulary')
            ->findOneBySysname($vocabularyName);

        $options = array();
        foreach ($vocabulary->getTerms() AS $term) {
            $options[$term->getId()] = $term->getName();
        }

        $element = new \Core\Form\Element\Select('term');
        $element->setMultiOptions($options);

        return $element;
    }

    public static function termsMultiCheckbox($vocabularyName)
    {
        $vocabulary = \Zend_Registry::get('doctrine')
            ->getRepository('Taxonomy\Model\Vocabulary')
            ->findOneBySysname($vocabularyName);

        $options = array();
        foreach ($vocabulary->getTerms() AS $term) {
            $options[$term->getId()] = $term->getName();
        }

        $element = new \Core\Form\Element\MultiCheckbox('terms');
        $element->setMultiOptions($options);

        return $element;
    }
}