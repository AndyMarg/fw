<?php

namespace vendor\core\base;

use vendor\core\Config;

class View
{
    /**
     * @var Контроллер, из которого вызывается текущий вид
     */
    private $controller;

    /**
     * @var array Массив скриптов из текущего вида
     */
    private $scripts = [];

    public function __construct($controller)
    {
        // контроллер, создавший вид
        $this->controller = $controller;
    }

    /**
     * Возвращает контент любого вида для любого контроллера
     * При этом переменные и настройки беруться из текущего контроллера.
     *
     * @param object $controller Текущий контроллер
     * @param string $view Вид для рендеринга
     * @param array $vars Массив переменных
     * @return string Контент вида
     */
    public static function getViewContent($controller, $view, $vars)
    {
        // извлекаем переменные
        if (is_array($vars))
            extract($vars);

        // подключаем вид (сохраняем буфер вывода в локальную переменную для последующего вывода в шаблоне)
        $file_view = Config::instance()->path->views . "/{$controller->getName()}/{$view}.php";
        ob_start();
        if (is_file($file_view)) {
            require $file_view;
        } else {
            echo "Не найден вид <b>{$file_view}</b><br>";
        }
        return ob_get_clean();
    }

    /***
     * Рендеринг страницы
     *
     * @param $vars Массив переменных для использовани в виде/шаблоне. Передается из контроллера
     */
    public function render($vars)
    {
        // META INFO
        $meta = $this->controller->getMeta();

        // извлекаем переменные
        if (is_array($vars))
            extract($vars);

        // получаем контент вида в переменую
        $content = self::getViewContent($this->controller, $this->controller->getView(), $vars);

        // подключаем и выводим шаблон (вид в шаблоне берется из локальной переменной)
        // если вывод шаблона и, соответственно, вида не заблокирован (нужно для AJAX)
        if (false !== $this->controller->getLayout()) {
            $file_layout = Config::instance()->root . "/app/views/layouts/{$this->controller->getLayout()}.php";
            if (is_file($file_layout)) {
                // вырезаем скрипты из контента вида
                $content = $this->cutScripts($content);
                // если есть скрипты, то помещаем в локальный массив $scripts,
                // который будет использоваться для вывода скриптов в конце  шаблона
                if (!empty($this->scripts)) {
                    $scripts = $this->scripts['scripts'];
                }
                // подключаем шаблон
                require $file_layout;
            } else {
                echo "Не найден шаблон <b>{$file_layout}</b><br>";
            }
        }
    }

    /**
     * Вырезает js-скрипты из вида и сохраняет их в массиве
     *
     * @param $content Контент вида
     * @return mixed Контент без скриптов
     */
    private function cutScripts($content)
    {
        $pattern = "#(?P<scripts><script.*?>.*?<\/script>)#is";
        preg_match_all($pattern, $content, $this->scripts);
        if (!empty($this->scripts)) {
            $content = preg_replace($pattern, '', $content);
        }
        return $content;
    }

}