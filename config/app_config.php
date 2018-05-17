<?php

/**
 * Пользовательская конфигурация (элемент root - ОБЯЗАТЕЛЕН!)
 */
$app_config_data = [
    'root' => $_SERVER['DOCUMENT_ROOT'],
    'db' => [
        'dns' => 'mysql:host=localhost;dbname=fw;charset=utf8',
        'user' => 'marg',
        'pass' => 'letmedoit'
    ]
];