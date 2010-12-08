<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'AllTests::main');
}

require_once __DIR__ . '/bootstrap.php';

class AllTests
{
    public static function main()
    {
        \PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new \PHPUnit_Framework_TestSuite('All Tests');

        require_once(TESTS_PATH . '/CMSAllTests.php');
        $suite->addTest(CMSAllTests::suite());
        $suite->addTest(\Integration\AllTests::suite());
        $suite->addTest(\System\AllTests::suite());

        return $suite;
    }
}