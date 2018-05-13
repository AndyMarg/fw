<?php

namespace vendor\core\base;

use vendor\core\Config;

class View
{
    private $route = [];
    private $layout;
    private $view;

    public function __construct($route, $layout, $view = '')
    {
        $this->route = $route;
        // если $layout === false, то шаблон не выводится
        if (false === $layout) {
            $this->layout = false;
        } else {
            $this->layout = $layout ?: Config::DEFAULT_LAYOUT;
        }

        $this->layout = $layout;
        $this->view = $view;
    }

    public function render($vars)
    {
        // извлекаем переменные
        if (is_array($vars))
            extract($vars);

        // подключаем вид (сохраняем буфер вывода в локальную переменную для последующего вывода в шаблоне)
        $file_view = DIR_ROOT . "/app/views/{$this->route['controller']}/{$this->view}.php";
        ob_start();
        if (is_file($file_view)) {
            require $file_view;
        } else {
            echo "Не найден вид <b>{$file_view}</b><br>";
        }
        $content = ob_get_clean();

        // подключаем и выводим шаблон (вид в шаблоне берется из локальной переменной)
        // если вывод шаблона и, соответственно, вида не заблокирован (нужно для AJAX)
        if (false !== $this->layout) {
            $file_layout = DIR_ROOT . "/app/views/layouts/{$this->layout}.php";
            if (is_file($file_layout)) {
                require $file_layout;
            } else {
                echo "Не найден шаблон <b>{$file_layout}</b><br>";
            }
        }
    }


}