<?php
namespace Core\Model;

require_once 'PHPUnit/Framework.php';
//require_once '../../../bootstrap.php';

//use \Mockery as m;

/**
 * Test class for Address Model.
 */
class AddressTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function tearDown()
    {
        //m::close();
    }

    public function testToArray()
    {
        $data = array(
            'id' => null,
            'addressLine1' => 'addressLine1',
            'addressLine2' => 'addressLine2',
            'city' => 'city',
            'state' => 'state',
            'zip' => '12345'
        );
        
        $address = new Address();
        $address->fromArray($data);

        $this->assertEquals($data, $address->toArray());
    }
}