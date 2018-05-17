<?php

require_once '../vendor/libs/utils.php';    // утилиты для отладки
require_once '../config/app_config.php';    // массив с конфигурацией фрейймворка
require_once '../vendor/core/Config.php';   // Менеджер конфигурации

use vendor\core\Router;
use vendor\core\Config;

error_reporting(E_ALL);

// инициализация и конфигурация фреймворка
$config = Config::instance();
$config->init($app_config_data);

// ******************** пользовательские маршруты ************************
// пользоватедьские маршруты должны определяться ДО маршрутов по умолчанию
Router::add('^/page/(?P<action>[a-z-]+)/(?P<alias>[a-z-]+)$', ['controller' => 'Page']);
Router::add('^/page/(?P<alias>[a-z-]+)$', ['controller' => 'Page', 'action' => 'view']);

//**************** дефолтные маршруты ****************************
// маршрут по умолчанию для пустого URI
Router::add('^$', ['controller' => $config->defaults->controller, 'action' => $config->defaults->action]);
// маршрут по умолчанию для URI в формате /controller/action
Router::add('^/(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');

$uri = rtrim($_SERVER['REQUEST_URI'],'/');
Router::dispatch($uri);


