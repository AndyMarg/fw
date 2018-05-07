<?php

$uri = rtrim($_SERVER['REQUEST_URI'],'/');

require_once '../vendor/core/Router.php';
require_once '../vendor/libs/utils.php';

/*
Router::add('/posts/add', ['controller' => 'Posts', 'action' => 'add']);
Router::add('/posts', ['controller' => 'Posts', 'action' => 'index']);
Router::add('', ['controller' => 'Main', 'action' => 'index']);
*/

// маршрут по умолчанию для пустого URI
Router::add('^$', ['controller' => 'Main', 'action' => 'index']);
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
