<?php

/**
 * Пользовательская конфигурация (передается в Config->Init() для инициализации фреймворка) (элемент root - ОБЯЗАТЕЛЕН!)
 */
define('ROOT', $_SERVER['DOCUMENT_ROOT']);

$app_config_data = [
    'root' => ROOT,

    'path' => [
        'temp' => ROOT . '/tmp',
        'cache' => ROOT . '/tmp/cache',
        'views' => ROOT . '/app/views',
        'controllers' => ROOT . '/app/controllers',
        'admin_controllers' => ROOT . '/app/controllers/admin',
        'error_views' => ROOT . '/errors/views',
        'error_log' => ROOT . '/errors/log'
    ],
    'db' => [
        'dns' => 'mysql:host=localhost;dbname=fw;charset=utf8',
        'user' => 'marg',
        'pass' => 'letmedoit'
    ],
    'debug' => [
        'debugging' => 1,
        'logging' => 0,
        'logfile' => 'error.log',
        'dev_error_view' => 'dev.php',
        'prod_error_view' => 'prod.php',
        'view_404' => '404.php'
    ],
    'widgets' => [
        'menu' => [
            'table' => 'categories',
            'template' => ROOT . '/app//widgets/menu/tpl/list_menu.tpl.php',
            'cache_key' => 'list_menu',
            'cache_time' => 1800
        ]
    ],

];