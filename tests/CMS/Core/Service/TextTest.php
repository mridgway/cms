<?php
namespace Core\Service;

require_once 'PHPUnit/Framework.php';
//require_once '../../../bootstrap.php';

use \Mockery as m;

/**
 * Test class for Text Service.
 */
class TextTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function tearDown()
    {
        m::close();
    }

    public function testGetShared()
    {
        $mock = m::mock();
        $mock->shouldReceive('findSharedText');

        $em = m::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('getRepository')->with('Core\Model\Content\Text')->andReturn($mock);

        $textService = new \Core\Service\Text($em);

        $textService->getShared();
    }

    public function testCreate()
    {
        $em = m::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('beginTransaction');
        $em->shouldReceive('rollback');
        $em->shouldReceive('close');
        $em->shouldReceive('persist');
        $em->shouldReceive('flush');
        $em->shouldReceive('commit');

        $textService = new \Core\Service\Text($em);

        $text = new \Core\Model\Content\Text('title', 'content', false);
        $newText = $textService->create('title', 'content', false);
        $this->assertEquals($text, $newText);

        $text = new \Core\Model\Content\Text('title', 'content');
        $newText = $textService->create('title', 'content');
        $this->assertEquals($text, $newText);
    }

    public function testUpdate()
    {
        $em = m::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('flush')->once();

        $textService = m::mock(new \Core\Service\Text($em), array(m::BLOCKS => array('update')));
        $textService->shouldReceive('getEntityManager')->andReturn($em);

        $text = m::mock('Core\Model\Content\Text');
        $text->shouldReceive('setTitle')->with('newTitle')->once();
        $text->shouldReceive('setContent')->with('newContent')->once();

        $textService->update($text, 'newTitle', 'newContent');
    }

    public function testDelete()
    {
        $text = m::mock('Core\Model\Content\Text');
        $text->shouldReceive('getShared')->once()->andReturn(false);
        $text->shouldReceive('getShared')->once()->andReturn(true);

        $em = m::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('remove')->once()->with($text);

        $textService = new \Core\Service\Text($em);

        $textService->delete($text);
        $textService->delete($text);
    }
}