<?php

namespace Core\Model\Frontend;

require_once 'PHPUnit/Framework.php';

/**
 * Test class for Simple.
 */
class SimpleTest extends \PHPUnit_Framework_TestCase
{
    protected $simple;

    protected function setUp()
    {
        $this->simple = new Simple();
    }

    protected function tearDown()
    {
    }

    public function testSuccess()
    {
        $this->simple->success();
        $code = new Code(0, 'Success');

        $this->assertEquals($code, $this->simple->code);

        $this->simple->success('new');
        $code = new Code(0, 'new');
        $this->assertEquals($code, $this->simple->code);
    }

    public function testFail()
    {
        $this->simple->fail();
        $code = new Code(1, 'Fail');

        $this->assertEquals($code, $this->simple->code);

        $this->simple->fail('new');
        $code = new Code(1, 'new');
        $this->assertEquals($code, $this->simple->code);
    }
}