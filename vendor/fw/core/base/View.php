<?php

namespace fw\core\base;

use fw\core\Config;

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

    // <META INFO> title, decription, frameworks
    private static $meta = ['title' => 'Default title', 'description' => '', 'keywords' => ''];

    public function __construct($controller)
    {
        // контроллер, создавший вид
        $this->controller = $controller;
    }

    /**
     * Установить мета-данные страницы
     *
     * @param string $title Заголовор
     * @param string $description Описание
     * @param string $keywords Ключевые слова
     */
    public static function setMeta($title = '', $description = '', $keywords = '')
    {
        if (!empty($title)) self::$meta['title'] = $title;
        if (!empty($description)) self::$meta['description'] = $description;
        if (!empty($keywords)) self::$meta['keywords'] = $keywords;
    }

    /**
     * @return array Возвращает массив мета-данных страницы
     */
    public static function getMeta()
    {
        return self::$meta;
    }

    /**
     * Выводит HTML с метаданными страницы
     */
    public static function printMeta()
    {
        echo '<title>' . self::$meta['title'] . '</title>' . "\n";
        if (!empty(self::$meta['description']))
            echo '<meta name="description" content="' . self::$meta['description'] . '">' . "\n";
        if (!empty(self::$meta['keywords']))
            echo '<meta name="keywords" content="' . self::$meta['keywords'] . '">' . "\n";
    }

    /**
     * Возвращает контент любого вида для любого контроллера
     * При этом переменные и настройки беруться из текущего контроллера.
     *
     * @param object $controller Текущий контроллер
     * @param string $view Вид для рендеринга
     * @param array $vars Массив переменных
     * @return string Контент вида
     * @throws \Exception
     */
    public static function getViewContent($controller, $view, $vars)
    {
        // извлекаем переменные
        if (is_array($vars))
            extract($vars);

        // подключаем вид (сохраняем буфер вывода в локальную переменную для последующего вывода в шаблоне)
        $config = Config::instance();
        $path = ($controller->isAdmin()) ? $config->path->admin_views : $config->path->views;
        $file_view = $path . "/{$controller->getName()}/{$view}.php";
        ob_start();
        if (is_file($file_view)) {
            require $file_view;
        } else {
            throw new \Exception("Не найден вид <b>{$file_view}</b><br>");
        }
        return ob_get_clean();
    }

    /***
     * Рендеринг страницы
     *
     * @param $vars Массив переменных для использовани в виде/шаблоне. Передается из контроллера
     * @param $compress true, если необходимо сжать вывод
     * @throws \Exception
     */
    public function render($vars, $compress = false)
    {
        // извлекаем переменные
        if (is_array($vars))
            extract($vars);

        // получаем контент вида в переменую
        $content = self::getViewContent($this->controller, $this->controller->getView(), $vars);

        // подключаем и выводим шаблон (вид в шаблоне берется из локальной переменной)
        // если вывод шаблона и, соответственно, вида не заблокирован (нужно для AJAX)
        if (false !== $this->controller->getLayout()) {
            $config = Config::instance();
            $path = ($this->controller->isAdmin()) ? $config->path->admin_views : $config->path->views;
            $file_layout = $path . "/layouts/{$this->controller->getLayout()}.php";
            if (is_file($file_layout)) {
                // вырезаем скрипты из контента вида
                $content = $this->cutScripts($content);
                // если есть скрипты, то помещаем в локальный массив $scripts,
                // который будет использоваться для вывода скриптов в конце  шаблона
                if (!empty($this->scripts)) {
                    $scripts = $this->scripts['scripts'];
                }
                // подключаем шаблон
                // если необходимо, сжимаем вывод
                if ($compress) {
                    ob_start("ob_gzhandler");
                    header("Content-Encoding: gzip");
                } else {
                    ob_start();
                }
                require $file_layout;
                $content = ob_get_contents();
                ob_clean();
                echo $content;
            } else {
                throw new \Exception("Не найден шаблон <b>{$file_layout}</b><br>");
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