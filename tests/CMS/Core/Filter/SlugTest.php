<?php
namespace Core\Filter;

require_once 'PHPUnit/Framework.php';
require_once __DIR__ . '/../../../bootstrap.php';

/**
 * Test class for Slug Filter.
 */
class SlugTest extends \PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    public function testFilter()
    {
        $filter = new \Core\Filter\Slug();
        
        $string = 'here is a new slug';
        $slug = 'here-is-a-new-slug';
        $newSlug = $filter->filter($string);
        $this->assertEquals($slug, $newSlug);

        $string = 'Here iS a new--slug?';
        $slug = 'here-is-a-new-slug';
        $newSlug = $filter->filter($string);
        $this->assertEquals($slug, $newSlug);
    }
}