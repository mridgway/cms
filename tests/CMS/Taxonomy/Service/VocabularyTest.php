<?php
namespace Taxonomy\Service;

require_once 'PHPUnit/Framework.php';
//require_once '../../../bootstrap.php';

use \Mockery as m;

/**
 * Test class for Vocabulary Service.
 */
class VocabularyTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function tearDown()
    {
        m::close();
    }

    public function testCreate()
    {
        $vocabulary = new \Taxonomy\Model\Vocabulary('name', 'sysname', 'description');
        
        $em = m::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('persist')->once();
        $em->shouldReceive('flush')->once();

        $vocabularyService = new \Taxonomy\Service\Vocabulary($em);

        $newVocab = $vocabularyService->create('name', 'sysname', 'description');

        $this->assertEquals($vocabulary, $newVocab);
    }

    public function testGetVocabulary()
    {
        $vocabulary = new \Taxonomy\Model\Vocabulary('name', 'sysname', 'description');

        $repository = m::mock();
        $repository->shouldReceive('find')->with(1)->once()->andReturn($vocabulary);
        $repository->shouldReceive('findOneBySysname')->with('howto:category')->once()->andReturn($vocabulary);

        $em = m::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('getRepository')->with('Taxonomy\Model\Vocabulary')->twice()->andReturn($repository);

        $vocabularyService = new \Taxonomy\Service\Vocabulary($em);

        $newVocabulary = $vocabularyService->getVocabulary(1);
        $this->assertEquals($vocabulary, $newVocabulary);

        $newVocabulary = $vocabularyService->getVocabulary('howto:category');
        $this->assertEquals($vocabulary, $newVocabulary);
    }
}