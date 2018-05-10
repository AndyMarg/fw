<?php

namespace vendor\core\base;


abstract class Controller
{
    private $route = [];
    //private $view;

    public function __construct ($route)
    {
        $this->route = $route;
        //$this->view = $route['action'];
        //include DIR_ROOT . "/app/views/{$route['controller']}/{$this->view}.php";
    }

    public function getRoute()
    {
        return $this->route;
    }

}