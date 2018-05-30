<?php

namespace app\controllers\admin;


use vendor\fw\core\base\Controller;
use vendor\fw\core\Config;

class AppController extends Controller
{
    public function __construct($route)
    {
        parent::__construct($route, true);
        $this->setLayout(Config::instance()->defaults->admin_layout);
    }
}