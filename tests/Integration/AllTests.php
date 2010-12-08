<?php

namespace Integration;

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'IntegrationAllTests::main');
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

        $suite->addTest(Core\AllTests::suite());
        $suite->addTest(Taxonomy\AllTests::suite());

        return $suite;
    }
}