<?php

require_once '../vendor/fw/libs/utils.php';    // утилиты для отладки
require_once '../config/app_config.php';       // массив с пользовательской конфигурацией фрейймворка

use fw\core\Router;
use fw\core\Config;

require __DIR__ . '/../vendor/autoload.php';

// инициализация и конфигурация фреймворка
$config = Config::instance();
$config->init($app_config_data);

// ******************** пользовательские маршруты ************************
// пользоватедьские маршруты должны определяться ДО маршрутов по умолчанию
Router::add('^/page/(?P<action>[a-z-]+)/(?P<alias>[a-z-]+)$', ['controller' => 'Page']);

//**************** дефолтные маршруты ****************************

// маршруты по умолчанию для админки
Router::add('^/' . $config->defaults->admin_url_prefix . '/?$', ['controller' => $config->defaults->admin_controller, 'action' => $config->defaults->admin_action]);
Router::add('^/' . $config->defaults->admin_url_prefix . '/(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');

// маршрут по умолчанию для пустого URI
Router::add('^$', ['controller' => $config->defaults->controller, 'action' => $config->defaults->action]);
// маршрут по умолчанию для URI в формате /controller/action
Router::add('^/(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');

$uri = rtrim($_SERVER['REQUEST_URI'],'/');
Router::dispatch($uri);


