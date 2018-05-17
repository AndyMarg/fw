<?php

namespace vendor\core;

/**
 * Class Cache  Менеджер кэша
 * @package vendor\core
 */
class Cache
{
    public function __construct() {}

    public function set($key, $data, $expiration = 3600)
    {
        $arr['data'] = $data;
        $arr['expiration'] = time() + $expiration;
        if (file_put_contents(Config::instance()->path->cache . '/' . md5($key) . '.txt', serialize($arr))) {
            return true;
        }
        return false;
    }

    public function get()
    {
        
    }
}