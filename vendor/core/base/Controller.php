<?php

namespace vendor\core\base;


abstract class Controller
{
    private $route = [];

    public function __construct ($route)
    {
        $this->route = $route;
    }

    public function getRoute()
    {
        return $this->route;
    }

}