<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'CMSAllTests::main');
}

require_once __DIR__ . '/../bootstrap.php';

class CMSAllTests
{
    public static function main()
    {
        \PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new CMSTestSuite('CMS Tests');

        $suite->addTest(Asset\AllTests::suite());
        $suite->addTest(Core\AllTests::suite());
        $suite->addTest(User\AllTests::suite());

        return $suite;
    }
}