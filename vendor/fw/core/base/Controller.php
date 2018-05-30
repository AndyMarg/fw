<?php

namespace vendor\fw\core\base;


use vendor\fw\core\Config;

abstract class Controller
{
    private $route = [];
    private $name;
    private $view;
    private $layout;

    /**
     * @var array Пользовательские данные (доступны из шаблона и вида)
     */
    private $vars = [];
    /**
     * @var True - если это контроллер админки
     */
    private $is_admin;

    public function __construct ($route, $is_admin = false)
    {
        $this->route = $route;
        $this->name = $route['controller'];
        $this->view = $route['action'];
        $this->layout = Config::instance()->defaults->layout;
        $this->is_admin = $is_admin;
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

    public function setVars($vars) {
        $this->vars = $vars;
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }

    public function setAdmin($is_admin)
    {
        $this->is_admin = $is_admin;
    }




    /**
     * @return bool True, если запрос поступил посредством XMLHttpRequest (AJAX)
     */
    function isAjax ()
    {
       return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest";
    }
}