<?php
namespace Core\Service;

require_once 'PHPUnit/Framework.php';
//require_once '../../../bootstrap.php';

use \Mockery as m;

/**
 * Test class for Address Service.
 */
class AddressTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function tearDown()
    {
        m::close();
    }
}