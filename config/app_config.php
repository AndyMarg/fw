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
        'views' => ROOT . '/app/views'
    ],
    'db' => [
        'dns' => 'mysql:host=localhost;dbname=fw;charset=utf8',
        'user' => 'marg',
        'pass' => 'letmedoit'
    ],
    'debug' => [
//        'debugging' => 0,
        'logging' => 1,
        'logfile' => ROOT. '/log/error.log'
    ]

];