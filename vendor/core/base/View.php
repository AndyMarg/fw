<?php

namespace vendor\core\base;

class View
{
    private $controller;

    public function __construct($controller)
    {
        // контроллер, создавший вид
        $this->controller = $controller;
    }

    public function render($vars)
    {
        // извлекаем переменные
        if (is_array($vars))
            extract($vars);

        // META INFO
        $meta = $this->controller->getMeta();

        // подключаем вид (сохраняем буфер вывода в локальную переменную для последующего вывода в шаблоне)
        $file_view = DIR_ROOT . "/app/views/{$this->controller->getName()}/{$this->controller->getView()}.php";
        ob_start();
        if (is_file($file_view)) {
            require $file_view;
        } else {
            echo "Не найден вид <b>{$file_view}</b><br>";
        }
        $content = ob_get_clean();

        // подключаем и выводим шаблон (вид в шаблоне берется из локальной переменной)
        // если вывод шаблона и, соответственно, вида не заблокирован (нужно для AJAX)
        if (false !== $this->controller->getLayout()) {
            $file_layout = DIR_ROOT . "/app/views/layouts/{$this->controller->getLayout()}.php";
            if (is_file($file_layout)) {
                require $file_layout;
            } else {
                echo "Не найден шаблон <b>{$file_layout}</b><br>";
            }
        }
    }

}