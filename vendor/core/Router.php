<?php

/**
 * Class Router
 * Маршрутизирует запросы
 */
class Router
{
    /**
     * @var array Таблица маршрутов
     */
    private static $routes = [];
    /**
     * @var array Отдельный маршрут
     */
    private static $route = [];

    /**
     * @return array Вернуть таблицу маршрутов
     */
    public static function getRoutes()
    {
        return self::$routes;
    }

    /**
     * @return array Вернуть маршрут
     */
    public static function getRoute()
    {
        return self::$route;
    }

    /**
     * Добавить маршрут в таблицу маршрутов
     *
     * @param string $regexp Регулярное выражения для определения соответствия маршруту пр поиске в таблице маршрутов
     * @param array $route Маршрут
     */
    public static function add($regexp, $route = []) {
        self::$routes[$regexp] = $route;
    }

    /**
     * Ищем маршрут по URI. Устанавливаем атрибут класса $route
     *
     * @param string $uri URI для поиска маршрута
     * @return bool true, если маршрут найден, иначе false
     */
    public static function matchRoute($uri)
    {
        foreach (self::$routes as $pattern => $route) {
            if (preg_match("/$pattern/i", $uri, $matches)) {
                debug($matches, 'Массив $matches');
                self::$route = $route;
                return true;
            }
        }
        return false;
    }

}