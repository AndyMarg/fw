<?php

namespace vendor\fw\core;

/**
 * Class Cache  Менеджер кэша
 * @package vendor\fw\core
 */
class Cache
{
    public function __construct() {}

    /**
     * Сохранение данных в кэше
     *
     * @param string $key Ключ кэша
     * @param $data Данные для сохранения
     * @param int $expiration Время актуальности кэша (в секундах)
     * @return bool Удалось ли сохранить данные
     */
    public function set($key, $data, $expiration = 3600)
    {
        $arr['data'] = $data;
        $arr['expiration'] = time() + $expiration;
        if (file_put_contents(Config::instance()->path->cache . '/' . md5($key) . '.txt', serialize($arr))) {
            return true;
        }
        return false;
    }

    /**
     * Получение данных из кэша
     *
     * @param $key  Ключ кэша
     * @return bool Данные (или false) при неудаче
     */
    public function get($key)
    {
        $file = Config::instance()->path->cache . '/' . md5($key) . '.txt';
        if (file_exists($file)) {
            $data = unserialize(file_get_contents($file));
            if (time() <= $data['expiration']) {
                return $data['data'];
            }
            unlink($file);
        }
        return false;
    }

    /**
     * Очистка данных в кэще
     *
     * @param $key Ключ кэша
     */
    public function clear($key)
    {
        $file = Config::instance()->path->cache . '/' . md5($key) . '.txt';
        if (file_exists($file)) {
            unlink($file);
        }
    }
}