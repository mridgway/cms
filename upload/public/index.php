<?php

if (!defined('APPLICATION_PATH')) {
    define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../CMS'));
}

if (!defined('APPLICATION_ROOT')) {
    define('APPLICATION_ROOT', realpath(dirname(__FILE__) . '/..'));
}

if (!defined('APPLICATION_ENV')) {
    if (getenv('APPLICATION_ENV')) {
        $env = getenv('APPLICATION_ENV');
    } else {
        $env = 'production';
    }
    define('APPLICATION_ENV', $env);
}

set_include_path(
    APPLICATION_PATH . PATH_SEPARATOR
    . APPLICATION_ROOT . '/library' . PATH_SEPARATOR
    . get_include_path()
);

require_once 'ZendX/Application53/Application.php';
$application = new \ZendX\Application53\Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/application.ini'
);

$application->bootstrap()->run();
