<?php
namespace Core\Model;

require_once 'PHPUnit/Framework.php';
require_once __DIR__ . '/../../../bootstrap.php';

/**
 * Test class for AbstractActivity.
 * Generated by PHPUnit on 2009-12-16 at 10:46:52.
 */
class AbstractActivityTest extends \CMSTestCase
{
    public function testGetModuleName()
    {
        $activity = new ConcreteActivity();
        $moduleName = $activity->getModuleName();

        $this->assertEquals('Core', $moduleName);
    }

    public function testGetPartialPath()
    {
        $activity = new ConcreteActivity();
        $script = $activity->getPartialPath();

        $this->assertEquals('Activity/concreteActivity/default.phtml', $script);
    }
}

class ConcreteActivity extends AbstractActivity{}