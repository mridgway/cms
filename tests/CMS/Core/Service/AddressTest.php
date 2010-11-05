<?php
namespace Core\Service;

require_once 'PHPUnit/Framework.php';
require_once '../../../bootstrap.php';

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

    public function testCreate()
    {
        $data = array(
            'addressLine1' => 'line1',
            'addressLine2' => 'line2',
            'city' => 'city',
            'state' => 'state',
            'zip' => '12345'
        );

        $address = new \Core\Model\Address();
        $address->setData($data);

        $em = m::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('persist')->never();
        $em->shouldReceive('flush')->never();

        $addressService = m::mock(new \Core\Service\Address($em), array(m::BLOCKS => array('create')));

        $newAddress = $addressService->create($data);
        $this->assertEquals($address, $newAddress);
    }
}