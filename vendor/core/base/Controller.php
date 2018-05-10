<?php

namespace vendor\core\base;


use vendor\core\Config;

abstract class Controller
{
    private $route = [];
    private $view;
    private $layout = Config::DEFAULT_LAYOUT;

    /**
     * @var array Пользовательские данные (доступны из шаблона и вида)
     */
    private $vars = [];

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

    public function getView()
    {
       $v = new View($this->route, $this->layout, $this->view);
       $v->render($this->vars);
    }

    public function setView($view)
    {
        $this->view = $view;
    }

    public function __construct ($route)
    {
        $this->route = $route;
        $this->view = $route['action'];
    }

    public function setVars($vars) {
        $this->vars = $vars;
    }

}