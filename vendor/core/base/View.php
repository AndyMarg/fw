<?php

namespace vendor\core\base;

use vendor\core\Config;

class View
{
    private $route = [];
    private $layout;
    private $view;

    public function __construct($route, $layout = Config::DEFAULT_LAYOUT, $view = '')
    {
        $this->route = $route;
        $this->layout = $layout;
        $this->view = $view;
    }

    public function render() {
        $file_view = DIR_ROOT . "/app/views/{$this->route['controller']}/{$this->view}.php";
        ob_start();
        if (is_file($file_view)) {
            require $file_view;
        } else {
            echo "Не найден вид <b>{$file_view}</b><br>";
        }
        $content = ob_get_clean();

        $file_layout = DIR_ROOT . "/app/views/layouts/{$this->layout}.php";
        if (is_file($file_layout)) {
            require $file_layout;
        } else {
            echo "Не найден шаблон <b>{$file_layout}</b><br>";
        }

    }
}