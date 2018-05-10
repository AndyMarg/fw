<?php

namespace vendor\core\base;


use vendor\core\Config;

abstract class Controller
{
    private $route = [];
    private $view;
    private $layout = Config::DEFAULT_LAYOUT;

    public function __construct ($route)
    {
        $this->route = $route;
        $this->view = $route['action'];
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getView()
    {
       $v = new View($this->route, $this->layout, $this->view);
       $v->render();
    }

}