<?php

namespace app\controllers;

use app\models\Main;

class MainController extends AppController
{
    public function __construct($route)
    {
        parent::__construct($route);
        //$this->setLayout('main');
    }

    public function indexAction()
    {
        $model = new Main();
        $posts = $model->findAll();
        $title = 'PAGE TITLE';
        $this->setVars(compact('title', 'posts'));
    }

}