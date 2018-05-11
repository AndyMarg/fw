<?php

namespace vendor\core;

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
     * Удаляет из запроса подстроку с параметрами GET (от "/"  или "/?", включая эти символы)
     *
     * @param string $uri Полная строка URI
     * @return string Очищенная строка
     */
    private static function removeQueryString($uri) {
        if (preg_match("#([/]?\?)#", $uri, $matches, PREG_OFFSET_CAPTURE)) {
            $uri = '/' . substr($uri,1, $matches[1][1]-1);
        }
        return $uri;
    }

    /**
     * Ищем маршрут по URI. Устанавливаем атрибут класса $route
     *
     * @param string $uri URI для поиска маршрута
     * @return bool true, если маршрут найден, иначе false
     */
    private static function matchRoute($uri)
    {
        foreach (self::$routes as $pattern => $route) {
            if (preg_match("#$pattern#i", $uri, $matches)) {
                foreach ($matches as $key => $val) {
                    if (is_string($key)) {
                        $route[$key] = $val;
                    }
                }
                // преобразуем формат названия контроллера из first-second-third в FirstSecondThird
                $route['controller'] = str_replace('-', '', ucwords($route['controller'], '-'));
                // если action не определен - устанавливаем по умолчанию
                if (!isset($route['action'])) {
                    $route['action'] = Config::DEFAULT_ACTION;
                }
                // преобразуем формат названия контроллера из first-second-third в firstSecondThird
                $route['action'] = lcfirst(str_replace('-', '', ucwords($route['action'], '-')));
                self::$route = $route;
                return true;
            }
        }
        return false;
    }

    /**
     * Диспетчеризация по маршруту (вызов метода контроллера)
     *
     * @param string $uri URI для поиска маршрута
     */
    public static function dispatch($uri) {
        $uri = self::removeQueryString($uri);
        if (self::matchRoute($uri)) {
            $controllerClass = 'app\\controllers\\' . self::$route['controller'] . 'Controller';
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass(self::$route);
                $action = self::$route['action'] . 'Action';
                if (method_exists($controller, $action)) {
                    $controller->$action();
                    $controller->getView();
                } else {
                    echo "Метод <b>$controllerClass::$action</b> НЕ найден<br>";
                }
            } else {
                echo "Контроллер <b>$controllerClass</b> НЕ найден<br>";
            }
        } else {
            http_response_code(404);
            include '404.html';
        }
    }

}