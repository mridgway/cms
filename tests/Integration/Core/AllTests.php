<?php

namespace Integration\Core;

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Integration\Core\AllTests::main');
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
        $suite = new \Integration\IntegrationTestSuite('Core Tests');

        $suite->addTestSuite('Integration\Core\Service\AbstractContentTest');
        $suite->addTestSuite('Integration\Core\Service\PageRouteTest');

        return $suite;
    }
}
