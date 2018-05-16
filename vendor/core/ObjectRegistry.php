<?php

namespace vendor\core;

/**
 * Объекты, автоматически создаваемые при загрузке или созданные по требованию.
 * Шаблон Registry. Обращение по алиасу.
 *
 * Class ObjectRegistry
 * @package vendor\core
 */
class ObjectRegistry
{
    private $objects = [];
    private static $instance;

    private function __construct() {}

    /**
     * @return ObjectRegistry Синглетон
     */
        public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * "Магический метод". Вызывается при получении несуществующего свойства.
     * Возвращает объект по алиасу, если существует, иначе ошибка.
     *
     * @param string $name Алиас объекта
     * @return mixed|null Объект
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->objects)) {
            return $this->objects[$name];
        }
        trigger_error("No defined auto created object: \"$name\"", E_USER_NOTICE);
        return null;
    }

    /**
     * "Магический метод". Вызывается при установке несуществующего свойства
     * Добавляет уже созданный объект в реестр
     *
     * @param string $name Алиас объекта (ключ массива objects)
     * @param $object Готовый объект. Если не объект - игнорируется.
     */
    public function __set($name, $object)
    {
        if (is_object($object)) {
            $this->objects[$name] = $object;
        }
    }

    /**
     * "Магический метод". Вызывается при проверке существования свойства
     * @param string $name Алиас объекта
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->objects[$name]);
    }

    /**
     * "Магический метод". Вызыается при удалении несуществующего свойства
     * @param string $name Алиас объекта
     */
    public function __unset($name)
    {
        unset($this->objects[$name]);
    }

    /**
     * Создает объект и добавляет его вреестр
     *
     * @param string $name Алиас объекта
     * @param string $class Полное имя объекта (включая пространство имен)
     */
    public function add($name, $class)
    {
        if (empty($this->objects[$name])) {
            $this->objects[$name] = new $class;
        }
    }

    /**
     * Генерирует и добавляет объекты к реестру на основе данных списка.
     *
     * @param array $classes Массив для генерации объектов и добавления их к реестру.
     * Вид элеменов массива "Алиас" -> "Полное имя объекта (с пространством имен)"
     *
     */
    public function addFromArray(Array $classes)
    {
        foreach ($classes as $name => $class) {
            $this->add($name, $class);
        }
    }

    /**
     * Возвращает реестр объектов
     *
     * @return array Реестр объектов
     */
    public function getObjects()
    {
        return $this->objects;
    }

}