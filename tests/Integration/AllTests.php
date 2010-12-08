<?php

namespace Integration;

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Integration\AllTests::main');
}

require_once __DIR__ . '/../bootstrap.php';

class AllTests
{
    public static function main()
    {
        \PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new IntegrationTestSuite('Integration Tests');

        $suite->addTestSuite('Integration\Core\AllTests');
        $suite->addTestSuite('Integration\Taxonomy\AllTests');

        return $suite;
    }
}