<?php

namespace vendor\core;


/**
 * Class AutoObjects Объекты, автоматически создаваемые при загрузке
 * @package vendor\core
 */
class AutoObjects
{
    private $objects = [];

    private static $instance;

    private function __construct()
    {
    }

    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function add($name, $class)
    {
        if (empty($this->objects[$name])) {
            $this->objects[$name] = new $class;
        }
    }

    public function addFromArray(Array $classes)
    {
        foreach ($classes as $name => $class) {
            $this->add($name, $class);
        }
    }

    public function getObjects()
    {
        return $this->objects;
    }

}