<?php

require_once '../vendor/libs/utils.php';
require_once '../vendor/core/init.php';
require_once '../config/app_config.php';

use vendor\core\Router;
use vendor\core\Config;
use config\ConfigDB;

error_reporting(E_ALL);

// инициализация и конфигурация фреймворка
Init($_SERVER['DOCUMENT_ROOT'], $app_config_data);
Config::instance()->setDB(ConfigDB::DSN, ConfigDB::USER, ConfigDB::PASS);

// ******************** пользовательские маршруты ************************
// пользоватедьские маршруты должны определяться ДО маршрутов по умолчанию
Router::add('^/page/(?P<action>[a-z-]+)/(?P<alias>[a-z-]+)$', ['controller' => 'Page']);
Router::add('^/page/(?P<alias>[a-z-]+)$', ['controller' => 'Page', 'action' => 'view']);

//**************** дефолтные маршруты ****************************
// маршрут по умолчанию для пустого URI
Router::add('^$', ['controller' => Config::DEFAULT_CONTROLLER, 'action' => Config::DEFAULT_ACTION]);
// маршрут по умолчанию для URI в формате /controller/action
Router::add('^/(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');

$uri = rtrim($_SERVER['REQUEST_URI'],'/');
Router::dispatch($uri);


