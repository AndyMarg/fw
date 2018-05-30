<?php

namespace vendor\fw\core;

/**
 * Доступ к ассоциатовному массиву как к объекту. Ключи представлены как свойства объекта.
 *
 * Class ArrayAsObject
 * @package vendor\fw\core
 */
class ArrayAsObject
{
    private $data = [];

    public function __construct(array $data = []) {
        $this->data = $data;
    }

    /**
     * "Магический метод". Вызывается при получении несуществующего свойства.
     * Возвращает значение массива по ключу если существует, иначе ошибка.
     *
     * @param string $name Ключ массива
     * @return mixed|null Значение
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        trigger_error("Not defined item: \"$name\"", E_USER_NOTICE);
        return null;
    }

    /**
     * "Магический метод". Вызывается при установке несуществующего свойства
     * Добавляет/изменяет значение массива по ключу
     *
     * @param string $name Ключ массива. Если нет, то создается
     * @param $value Значение массива.
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * "Магический метод". Вызывается при проверке существования свойства
     * @param string $name Ключ массива
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * "Магический метод". Вызыается при удалении несуществующего свойства
     * @param string $name Ключ массива
     */
    public function __unset($name)
    {
        unset($this->data[$name]);
    }

    /**
     * Возвращает массив
     *
     * @return array Реестр объектов
     */
    public function getData()
    {
        return $this->data;
    }

}