<?php

use vendor\core\Router;
use vendor\core\Config;

error_reporting(E_ALL);

define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT']);

$uri = rtrim($_SERVER['REQUEST_URI'],'/');

require_once '../vendor/libs/utils.php';

// функция автозагрузки
spl_autoload_register(function ($class) {
    $file = DIR_ROOT . '/' . str_replace('\\', '/', $class) . '.php';
    if(is_file($file)) {
        require_once $file;
    }
});

// ******************** пользовательские маршруты ************************
// пользоватедьские маршруты должны определяться ДО маршрутов по умолчанию
Router::add('^/pages/?(?P<action>[a-z-]+)?$', ['controller' => 'PostsNew']);

//**************** дефолтные маршруты ****************************
// маршрут по умолчанию для пустого URI
Router::add('^$', ['controller' => Config::DEFAULT_CONTROLLER, 'action' => Config::DEFAULT_ACTION]);
// маршрут по умолчанию для URI в формате /controller/action
Router::add('^/(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');

Router::dispatch($uri);

