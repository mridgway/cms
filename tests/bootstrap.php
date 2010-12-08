<?php

error_reporting( E_ALL | E_STRICT );
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
date_default_timezone_set('America/New_York');
define('APPLICATION_ROOT', realpath(dirname(__FILE__) . '/../upload'));
define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../upload/CMS'));
define('APPLICATION_ENV', 'testing');
define('LIBRARY_PATH', realpath(dirname(__FILE__) . '/../upload/library'));
define('TESTS_PATH', realpath(dirname(__FILE__) . '/CMS'));
define('TEST_LIBRARY_PATH', realpath(dirname(__FILE__)) . '/library');
define('TESTS_ROOT', realpath(dirname(__FILE__)));

$_SERVER['SERVER_NAME'] = 'http://localhost';

$includePaths = array(TESTS_ROOT, TESTS_PATH, TEST_LIBRARY_PATH, APPLICATION_PATH, LIBRARY_PATH, get_include_path());
set_include_path(implode(PATH_SEPARATOR, $includePaths));

include('Zend/Loader/Autoloader.php');
Zend_Loader_Autoloader::getInstance()->registerNamespace('Mock\\');
Zend_Loader_Autoloader::getInstance()->registerNamespace('Modo\\');
Zend_Loader_Autoloader::getInstance()->registerNamespace('Doctrine\\');
Zend_Loader_Autoloader::getInstance()->registerNamespace('ZendX\\');

Zend_Loader_Autoloader::getInstance()->registerNamespace('Core\\');
Zend_Loader_Autoloader::getInstance()->registerNamespace('Asset\\');
Zend_Loader_Autoloader::getInstance()->registerNamespace('User\\');
Zend_Loader_Autoloader::getInstance()->registerNamespace('Blog\\');
Zend_Loader_Autoloader::getInstance()->registerNamespace('Taxonomy\\');
Zend_Loader_Autoloader::getInstance()->registerNamespace('CMS\\');
Zend_Loader_Autoloader::getInstance()->registerNamespace('Integration\\');
Zend_Loader_Autoloader::getInstance()->registerNamespace('System\\');

require_once('CMSTestCase.php');
require_once('CMSTestSuite.php');

require_once 'Mockery/Loader.php';
$loader = new \Mockery\Loader;
$loader->register();

require_once LIBRARY_PATH . '/symfony/dependency-injection/lib/sfServiceContainerAutoloader.php';
sfServiceContainerAutoloader::register();

Zend_Session::$_unitTestEnabled = true;
Zend_Session::start();