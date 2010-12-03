<?php
namespace Core\Service\IntegrationTests;

require_once 'PHPUnit/Framework.php';
require_once __DIR__ . '/../../../../bootstrap.php';

/**
 * Integration Test for Question Service.
 * This test assumes that the Geocode Module is installed and seeded.
 */
class ContentTest extends \CMS\CMSAbstractIntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->_sc->getService('doctrine')->beginTransaction();
    }

    protected function tearDown()
    {
        $this->_sc->getService('doctrine')->rollback();
        \Core\Module\Registry::destroy();
    }

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

        $content = new MockContent();
        $contentService = new MockContentService();
        $contentService->setTermService($termService);
        $contentService->setTerms($content, $terms);

        $this->assertEquals(\Doctrine\Common\Util\Debug::export($termCollection, 2), \Doctrine\Common\Util\Debug::export($content->getTags(), 2));
    }
}
class MockContent extends \Core\Model\Content {}
class MockContentService extends \Core\Service\Content
{
    public function setTerms(\Core\Model\Content $content, array $terms)
    {
        return $this->_setTerms($content, $terms);
    }
}