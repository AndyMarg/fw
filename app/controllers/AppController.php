<?php

namespace app\controllers;

use app\models\Main;
use fw\core\base\Controller;

class AppController extends Controller
{
    public function __construct($route)
    {
        parent::__construct($route);
        $model = new Main();
    }
}