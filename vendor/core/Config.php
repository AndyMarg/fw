<?php

namespace vendor\core;

/**
 * Class Config Конфигурация фреймворка (синглетон)
 * Доступ ко многим свойствам - через магические методы к массиву $registry.
 * Подмассивы в $registry представлены как объекты класса ArrayAsObject,
 * то есть доступ к элементам подмассивов возможен как к свойствам
 */
class Config
{
    private $registry = [];             // массив для хранения конфигурации и доступа к элементам как к свойствам
    //private $objectRegistry;
    private $_root = 'UNDEFINED';       // путь к корню приложения
    private static $instance = null;    // для синглетона


    private function __construct() {
        //$this->objectRegistry = \vendor\core\ObjectRegistry::instance();
    }

    /**
     * Для синглетона
     * @return object Данный объект
     */
    public static  function instance()
    {
        if (null === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * Рекурсивный метод для добавления подмассивов (элементов) к $registry
     *
     * @param array $parent   Родительский элемен
     * @param array $child    Дочерний подмассив
     * @return mixed          Объект типа ArrayAsObject
     */
    private function addArrayToRegistry($parent, $child)
    {
        foreach ($child as $name => $value) {
            if(is_array($value)) {
                $parent[$name] = [];
                $next_array_as_object = $this->addArrayToRegistry($parent[$name], $value);
                $parent[$name] = new ArrayAsObject($next_array_as_object);
            } else {
                $parent[$name]  = $value;
            }
        }
        return $parent;
    }

    /**
     * Инициализация фреймворка (должен быть первым методом из фреймворка вызываемом в приложении)
     *
     * @param array $app_config_data  Массив пользовательской конфигурации
     */
    public function init(array $app_config_data) {
        // устанавливаем путь к корню приложения (для работы функции автозагрузки классов)
        $this->_root = $app_config_data['root'];

        // функция автозагрузки
        spl_autoload_register(function ($class) {
            $file = $this->getRoot() . '/' . str_replace('\\', '/', $class) . '.php';
            if(is_file($file)) {
                require_once $file;
            }
        });

        // массив системной конфигурации
        require_once 'config_data.php';
        // объединяем пользовательскую и системную конфингурацию
        $all_config_data = array_merge_recursive($app_config_data, $_config_data);
        // формируем реестр конфигурации
        $this->registry = $this->addArrayToRegistry($this->registry, $all_config_data);
    }

    /**
     * "Магический метод". Вызывается при получении неизвестного свойства.
     * Возвращает массив настроек раздела конфигурации, если существует, иначе ошибка.
     *
     * @param string $name Имя раздела конфигурации
     * @return mixed|null Объект
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->registry)) {
            return $this->registry[$name];
        }
        trigger_error("Not such item in configuration: \"$name\"", E_USER_NOTICE);
        return null;
    }

    /**
     * @return string Путь к корню приложения
     */
    public function getRoot()
    {
        return $this->_root;
    }

    /**
     * @return array Реестр конфигурации
     */
    public function getRegistry() {
        return $this->registry;
    }

}