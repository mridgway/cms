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
}