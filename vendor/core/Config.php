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
    //private $objectRegistry;

    private $root = 'UNDEFINED';

    private static $instance = null;

    private function __construct() {
        //$this->objectRegistry = \vendor\core\ObjectRegistry::instance();
    }

    public static  function instance()
    {
        if (null === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function addArrayToRegistry($parent, $arr)
    {
        debug($parent, 'parent');
        debug($arr, 'child');
        foreach ($arr as $name => $value) {
            if(is_array($value)) {
                $this->addArrayToRegistry($arr, $value);
            } else {
                $parent[$name] = $value;
            }
        }
    }
    
    public function init(array $app_config_data) {
        require_once 'config_data.php';
        $all_config_data = array_merge_recursive($app_config_data, $_config_data);
        $this->addArrayToRegistry($this->registry, $all_config_data);
    }

    /**
     * "Магический метод". Вызывается при получении неизвестного свойства.
     * Возвращает массив настроек раздела конфигурации, если существует, иначе ошибка.
     *
     * @param string $name Имя раздела конфигурации
     * @return mixed|null Объект
     */
    public function __get($section)
    {
        if (array_key_exists($section, $this->registry)) {
            return $this->registry[$section];
        }
        trigger_error("Not such section in configuration: \"$section\"", E_USER_NOTICE);
        return null;
    }

    public function setRoot($root)
    {
        $this->root = $root;
    }

    public function getRoot()
    {
        return $this->root;
    }

    public function set($section, $name, $value)
    {
        if (!array_key_exists($section, $this->registry)) {
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