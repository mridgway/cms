<?php

if (!defined('APPLICATION_PATH')) {
    define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../CMS'));
}

if (!defined('APPLICATION_ROOT')) {
    define('APPLICATION_ROOT', realpath(dirname(__FILE__) . '/..'));
}

set_include_path(
    APPLICATION_PATH . PATH_SEPARATOR
    . APPLICATION_ROOT . '/library' . PATH_SEPARATOR
    . get_include_path()
);

$i = array_search('-e', $_SERVER['argv']);

if (!$i) {
    $i = array_search('--environment', $_SERVER['argv']);
}

if ($i) {
    define('APPLICATION_ENV', $_SERVER['argv'][$i+1]);
}

require 'ZendX/Application53/Application.php';

$application = new \ZendX\Application53\Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/application.ini'
);

$application->bootstrap()->run();