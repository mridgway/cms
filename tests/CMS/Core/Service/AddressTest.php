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

    public function testCreate()
    {
        $data = array(
            'addressLine1' => '111 Road',
            'addressLine2' => '222 Road',
            'city' => 'city',
            'state' => 'PA',
            'zip' => '12345'
        );

        $address = new \Core\Model\Address();
        $address->fromArray($data);

        $em = m::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('persist');
        $em->shouldReceive('flush');

        $addressService = new \Core\Service\Address($em);

        $newAddress = $addressService->create($data);

        $this->assertEquals($address, $newAddress);
    }
}