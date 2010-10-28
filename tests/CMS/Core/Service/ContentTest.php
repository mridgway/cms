<?php
namespace Core\Service;

require_once 'PHPUnit/Framework.php';
//require_once '../../../bootstrap.php';

use \Mockery as m;

/**
 * Test class for Content Service.
 */
class ContentTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function tearDown()
    {
        m::close();
    }

    public function testGetContent()
    {
        $mock = m::mock();

        $em = m::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('getReference')->with('Core\Model\Content', 1);

        $contentService = new \Core\Service\Content($em);

        $contentService->getContent(1);
    }
}