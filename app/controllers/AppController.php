<?php

namespace app\controllers;

use app\models\Main;
use vendor\core\base\Controller;

class AppController extends Controller
{
    private $menu;

    public function __construct($route)
    {
        parent::__construct($route);
        $model = new Main();
        $this->menu = \R::findAll('category');
    }

    public function getMenu()
    {
        return $this->menu;
    }

}