<?php

namespace fw\core;

/**
 * Объекты, автоматически создаваемые при загрузке или созданные по требованию.
 * Шаблон Registry. Обращение по алиасу.
 *
 * Class ObjectRegistry
 * @package fw\core
 */
class ObjectRegistry
{
    use TSingleton;

    private $objects = [];

    private function __construct() {}

    /**
     * "Магический метод". Вызывается при получении неизвестного свойства.
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
        trigger_error("Not defined auto created object: \"$name\"", E_USER_NOTICE);
        return null;
    }

    /**
     * "Магический метод". Вызывается при установке неизвестного свойства
     * Добавляет уже созданный объект в реестр
     *
     * @param string $name Алиас объекта (ключ массива objects)
     * @param $object Готовый объект. Если не объект - игнорируется.
     */
    public function __set($name, $object)
    {
        if (!array_key_exists($name, $this->objects)) {
            if (is_object($object)) {
                $this->objects[$name] = $object;
            }
        } else {
            trigger_error("Object \"$name\" already created before", E_USER_NOTICE);
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