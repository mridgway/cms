<?php

error_reporting( E_ALL | E_STRICT );
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
date_default_timezone_set('America/New_York');
define('APPLICATION_ROOT', realpath(dirname(__FILE__) . '/../upload'));
define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../upload/CMS'));
define('APPLICATION_ENV', 'loc');
define('LIBRARY_PATH', realpath(dirname(__FILE__) . '/../upload/library'));
define('TESTS_PATH', realpath(dirname(__FILE__) . '/CMS'));
define('TEST_LIBRARY_PATH', realpath(dirname(__FILE__)) . '/library');


$_SERVER['SERVER_NAME'] = 'http://localhost';

$includePaths = array(TESTS_PATH, TEST_LIBRARY_PATH, APPLICATION_PATH, LIBRARY_PATH, get_include_path());
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

require_once('CMSTestCase.php');
require_once('CMSTestSuite.php');

Zend_Session::$_unitTestEnabled = true;
Zend_Session::start();