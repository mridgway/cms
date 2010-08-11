<?php

namespace Modo;

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'AllTests::main');
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
        $suite = new \PHPUnit_Framework_TestSuite('Modo Tests');

        $suite->addTestSuite('Modo\Controller\Request\HttpTest');
        $suite->addTestSuite('Modo\Controller\Router\RewriteTest');
        $suite->addTestSuite('Modo\Orm\Model\AbstractModelTest');
        $suite->addTestSuite('Modo\Service\Query\ConstraintBuilderTest');

        return $suite;
    }
}