<?php
namespace Core\Service\Block;

require_once 'PHPUnit/Framework.php';
require_once '../../../bootstrap.php';

use \Mockery as m;

/**
 * Test class for DynamicBlock Service.
 */
class DynamicBlockTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function tearDown()
    {
        m::close();
    }
}