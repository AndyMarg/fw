<?php

namespace app\controllers\admin;


//use fw\core\base\Controller;
use fw\core\base\Controller;
use fw\core\Config;

class AppController extends Controller
{
    public function __construct($route)
    {
        parent::__construct($route, true);
        $this->setLayout(Config::instance()->defaults->admin_layout);
    }
}