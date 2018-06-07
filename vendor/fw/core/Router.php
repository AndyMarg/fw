<?php

namespace fw\core;

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
     * @var bool Если true - маршрутизация админки
     */
    private static $is_admin;

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
            if ($matches[1][1] === 0) {
                return "";
            } else {
                return '/' . substr($uri, 1, $matches[1][1] - 1);
            }
        }
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
                // отпределяем, является ли маршрут - маршрутом админки
                self::$is_admin = (strpos($pattern, '/' . Config::instance()->defaults->admin_url_prefix . '/')) ? true : false;
                // заполняем маршрут из URI
                foreach ($matches as $key => $val) {
                    if (is_string($key)) {
                        $route[$key] = $val;
                    }
                }
                // преобразуем формат названия контроллера из first-second-third в FirstSecondThird
                $route['controller'] = str_replace('-', '', ucwords($route['controller'], '-'));
                // если action не определен - устанавливаем по умолчанию
                if (!isset($route['action'])) {
                    $route['action'] = Config::instance()->defaults->action;
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
     * @throws \Exception
     */
    public static function dispatch($uri) {
        $uri = self::removeQueryString($uri);
        if (self::matchRoute($uri)) {
            $config = Config::instance();
            $path = (self::$is_admin) ? $config->path->admin_controllers : $config->path->controllers;
            $path = str_replace('/', '\\',  str_replace($config->root, '', $path)) . '\\';
            $controllerClass = $path .  self::$route['controller'] . 'Controller';
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass(self::$route);
                $action = self::$route['action'] . 'Action';
                if (method_exists($controller, $action)) {
                    $controller->$action();
                    $controller->showView();
                } else {
                    throw  new \Exception("Метод <b>$controllerClass::$action</b> НЕ найден");
                }
            } else {
                throw  new \Exception("Контроллер <b>$controllerClass</b> НЕ найден");

            }
        } else {
            throw new \Exception('Страница не найдена ...', 404);
        }
    }

}