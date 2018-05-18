<?php

namespace vendor\core\base;


use vendor\core\Config;

abstract class Controller
{
    private $route = [];
    private $name;
    private $view;
    private $layout;

    // <META INFO> title, decription, frameworks
    private $meta = [];

    /**
     * @var array Пользовательские данные (доступны из шаблона и вида)
     */
    private $vars = [];

    public function __construct ($route)
    {
        $this->route = $route;
        $this->name = $route['controller'];
        $this->view = $route['action'];
        $this->layout = Config::instance()->defaults->layout;
        $this->setMeta('Default title');
    }

    public function getLayout()
    {
        return $this->layout;
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }


    public function getRoute()
    {
        return $this->route;
    }

    public function showView()
    {
       $v = new View($this);
       $v->render($this->vars);
    }

    public function setView($view)
    {
        $this->view = $view;
    }

    public function getView()
    {
        return $this->view;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setMeta($title = '', $description = '', $keywords = '')
    {
        $this->meta['title'] = $title;
        $this->meta['description'] = $description;
        $this->meta['keywords'] = $keywords;
    }

    public function getMeta()
    {
        return $this->meta;
    }

    public function setVars($vars) {
        $this->vars = $vars;
    }

    /**
     * @return bool True, если запрос поступил посредством XMLHttpRequest (AJAX)
     */
    function isAjax ()
    {
       return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest";
    }
}