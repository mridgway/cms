<?php

namespace Taxonomy;

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Taxonomy\AllTests::main');
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
        $suite = new \CMSTestSuite('Taxonomy Tests');

        $suite->addTestSuite('Taxonomy\Service\VocabularyTest');

        return $suite;
    }
}
