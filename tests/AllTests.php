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
        $suite = new CMSTestSuite('All Tests');

        require_once(TESTS_PATH . '/CMSAllTests.php');
        $suite->addTest(CMSAllTests::suite());

        return $suite;
    }
}