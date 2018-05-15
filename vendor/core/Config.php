<?php

namespace vendor\core;

/**
 * Class Config Конфигурация фреймворка
 */
class Config
{
    const DEFAULT_CONTROLLER = 'Main';
    const DEFAULT_ACTION = 'index';
    const DEFAULT_LAYOUT = 'default';

    private $registry = [];

    private $root = 'UNDEFINED';

    private static $instance = null;

    private function __construct()
    {
        // функция автозагрузки
        spl_autoload_register(function ($class) {
            $file = $this->root . '/' . str_replace('\\', '/', $class) . '.php';
            if(is_file($file)) {
                require_once $file;
            }
        });
    }

    public static  function instance()
    {
        if (null === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function setRoot($root)
    {
        $this->root = $root;
    }

    public function getRoot()
    {
        return $this->root;
    }

    public function loadClasses(Array $classes)
    {
        foreach ($classes as $key => $class) {
            self::set('components', $key, new $class);
        }
    }

    public function set($section, $name, $value)
    {
        if (empty($this->registry[$section])) {
            $this->registry[$section] = [];
        }
        $this->registry[$section][$name] = $value;
    }

    public function get($section, $name)
    {
        return $this->registry[$section][$name];
    }

    public function setDB($dns, $user, $pass)
    {
        $this->set('database','dns', $dns);
        $this->set('database','user', $user);
        $this->set('database','pass', $pass);
    }

    public function getRegistry() {
        return $this->registry;
    }



}