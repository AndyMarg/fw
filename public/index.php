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
Router::add('^([a-z-]+)/([a-z-]+)$');


debug($uri, 'URI');

if (Router::matchRoute($uri)) {
    debug(Router::getRoute(), 'Таблица маршрутов');
} else {
    echo 'Маршрут не найден';
}
debug(Router::getRoutes());
