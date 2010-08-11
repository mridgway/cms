<?php

namespace Asset;

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Asset\AllTests::main');
}

require_once __DIR__ . '/../../bootstrap.php';

class AllTests
{
    public static function main()
    {
        \PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new \CMSTestSuite('Asset Tests');

        $suite->addTestSuite('Asset\Model\AssetTest');
        $suite->addTestSuite('Asset\Model\ExtensionTest');
        $suite->addTestSuite('Asset\Model\GroupTest');
        $suite->addTestSuite('Asset\Model\MimeTypeTest');
        $suite->addTestSuite('Asset\Model\SizeTest');
        $suite->addTestSuite('Asset\Model\TypeTest');

        return $suite;
    }
}