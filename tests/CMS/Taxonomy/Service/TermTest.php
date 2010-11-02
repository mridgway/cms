<?php
namespace Taxonomy\Service;

require_once 'PHPUnit/Framework.php';
//require_once '../../../bootstrap.php';

use \Mockery as m;

/**
 * Test class for Term Service.
 */
class TermTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function tearDown()
    {
        m::close();
    }

    public function testCreateTerm()
    {
        $data = array(
            'name' => 'name',
            'definition' => 'definition'
        );

        $vocabulary = new \Taxonomy\Model\Vocabulary('name', 'sysname', 'description');
        $term = new \Taxonomy\Model\Term('name', 'definition');
        $term->setVocabulary($vocabulary);

        $em = m::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('persist')->times(3);
        $em->shouldReceive('flush')->times(3);

        $vocabularyService = m::mock();
        $vocabularyService->shouldReceive('getVocabulary')->andReturn($vocabulary);

        $termService = m::mock(new \Taxonomy\Service\Term($em), array(m::BLOCKS => array('createTerm')));
        $termService->shouldReceive('getVocabularyService')->andReturn($vocabularyService);
        $termService->shouldReceive('getEntityManager')->andReturn($em);

        $newTerm = $termService->createTerm($vocabulary, $data);
        $this->assertEquals($term, $newTerm);

        $newTerm = $termService->createTerm('howto:category', $data);
        $this->assertEquals($term, $newTerm);

        $newTerm = $termService->createTerm(1, $data);
        $this->assertEquals($term, $newTerm);
    }
}