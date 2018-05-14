<?php

namespace vendor\core;


//spl_autoload_register(function ($class) {
//    $file = str_replace('\\', '/', $class) . '.php';
//    if(is_file($file)) {
//        require_once $file;
//    }
//});

/**
 * Class Config Конфигурация фреймворка
 */
class Config
{
    const DEFAULT_CONTROLLER = 'Main';
    const DEFAULT_ACTION = 'index';
    const DEFAULT_LAYOUT = 'default';

    private $registry = [];

    private $classes = [
        'cache' => 'vendor\core\Cache',
        'test' => 'vendor\core\Test'
    ];

    public function __construct()
    {
        $this->loadClasses($this->classes);
    }

    public function loadClasses(Array $classes)
    {
        foreach ($classes as $key => $class) {
            self::set('components', $key, $class);
        }
    }

    public function set($section, $name, $value)
    {
        if (empty($this->registry[$section])) {
            $this->registry[$section] = [];
        }
        $this->registry[$section][$name] = new $value;
    }

    public function getAll() {
        return $this->registry;
    }

}