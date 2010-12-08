<?php
namespace Integration\Taxonomy\Service;

require_once 'PHPUnit/Framework.php';
require_once __DIR__ . '/../../../bootstrap.php';

/**
 * Integration Test for Term Service.
 */
class TermTest extends \Integration\IntegrationTestCase
{
    public function testSetTags()
    {
        $terms = array(
            'tag1',
            'tag2'
        );
        
        $termService = $this->_sc->getService('termService');

        $term1 = $termService->getOrCreateTerm($terms[0], 'contentTags');
        $term2 = $termService->getOrCreateTerm($terms[1], 'contentTags');
        $termCollection = new \Doctrine\Common\Collections\ArrayCollection();
        $termCollection->add($term1);
        $termCollection->add($term2);

        $newTermCollection = new \Doctrine\Common\Collections\ArrayCollection($termService->getOrCreateTerms($terms, 'contentTags'));

        $this->assertEquals(\Doctrine\Common\Util\Debug::export($termCollection, 2), \Doctrine\Common\Util\Debug::export($newTermCollection, 2));
    }
}