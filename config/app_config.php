<?php

/**
 * Пользовательская конфигурация (элемент root - ОБЯЗАТЕЛЕН!)
 */
$app_config_data = [
    'root' => $_SERVER['DOCUMENT_ROOT'],

    'path' => [
        'temp' => $_SERVER['DOCUMENT_ROOT'] . '/tmp',
        'cache' => $_SERVER['DOCUMENT_ROOT'] . '/tmp/cache'
    ],

    'db' => [
        'dns' => 'mysql:host=localhost;dbname=fw;charset=utf8',
        'user' => 'marg',
        'pass' => 'letmedoit'
    ]
];