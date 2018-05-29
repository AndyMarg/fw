<?php

/**
 * Системная конфигурация
 */
$_config_data = [

    'path' => [
        'error_views' => 'views',
        'error_log' => ''
    ],

    'objects' => [
        'cache' => 'vendor\core\Cache'
    ],

    'defaults' => [
        'controller' => 'Main',
        'action' => 'index',
        'layout' => 'default'
    ],

    'debug' => [
        'debugging' => 1,
        'logging' => 0,
        'logfile' => '',
        'dev_error_view' => 'dev.php',
        'prod_error_view' => 'prod.php',
        'view_404' => '404.php'
    ],

    'widgets' => [
        'menu' => [
            'template' => 'tpl/default.tpl.php',
            'table' => '',
            'html_container' => 'ul',
            'container_class' => 'menu',
            'cache_time' => 3600,
            'cache_key' => ''
        ]
    ]

];