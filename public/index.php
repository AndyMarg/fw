<?php

$uri = rtrim($_SERVER['REQUEST_URI'],'/');

require_once '../vendor/core/Router.php';
require_once '../vendor/core/Config.php';
require_once '../vendor/libs/utils.php';

require_once '../vendor/core/Controller.php';
require_once '../app/controllers/Main.php';
require_once '../app/controllers/Posts.php';
require_once '../app/controllers/PostsNew.php';


// маршрут по умолчанию для пустого URI
Router::add('^$', ['controller' => Config::DEFAULT_CONTROLLER, 'action' => Config::DEFAULT_ACTION]);
// маршрут по умолчанию для URI в формате /controller/action
Router::add('^/(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');

Router::dispatch($uri);

//
//debug($uri, 'URI');
//
//if (Router::matchRoute($uri)) {
//    debug(Router::getRoute(), 'Текущий маршрут');
//} else {
//    echo "Маршрут не найден<br><br>";
//}
//debug(Router::getRoutes(),'Таблица маршрутов');

// 18 минут
