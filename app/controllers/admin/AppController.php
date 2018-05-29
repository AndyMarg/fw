<?php

namespace app\controllers\admin;


use vendor\core\base\Controller;
use vendor\core\Config;

class AppController extends Controller
{

    public function __construct($route)
    {
        parent::__construct($route);
        $this->setLayout(Config::instance()->defaults->admin_layout);
    }
}