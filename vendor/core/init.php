<?php

require_once '../vendor/core/Config.php';

/**
 * Инициализация фреймворка
 *
 * @param string $root Путь к корню приложения
 */
function Init($root)
{
    $config = \vendor\core\Config::instance();
    $config->setRoot($root);

    // создаем объекты заранее для последующего использования фреймворком
    $config->loadClasses([
        'cache' => 'vendor\core\Cache'
    ]);
}
