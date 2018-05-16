<?php

require_once '../vendor/core/Config.php';

/**
 * Инициализация фреймворка
 *
 * @param string $root Путь к корню приложения
 */
function Init($root, $app_config_data)
{
    $config = \vendor\core\Config::instance();
    $config->setRoot($root);
    $config->init($app_config_data);
    //debug($config->app);

    // функция автозагрузки
    spl_autoload_register(function ($class) {
        $config = \vendor\core\Config::instance();
        $file = $config->getRoot() . '/' . str_replace('\\', '/', $class) . '.php';
        if(is_file($file)) {
            require_once $file;
        }
    });

    $a = new ArrayObject($app_config_data['db']);
    debug($a->dns);

    // временная отладка
    $auto = \vendor\core\ObjectRegistry::instance();
    $auto->addFromArray([
        'cache' => 'vendor\core\Cache'
    ]);

}
