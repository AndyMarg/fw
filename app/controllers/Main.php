<?php

namespace app\controllers;

class Main extends AppController
{
    public function __construct($route)
    {
        parent::__construct($route);
        //$this->setLayout('main');
    }

    public function indexAction() {
        //$this->setLayout(false);
        //$this->setView('test');

        $name = 'Андрей';
        $hi = 'Hello';
        $colors = [
            'black' => 'Черный',
            'red' => 'Красный'
        ];
        $title = 'PAGE TITLE';

        $this->setVars(compact('name','hi','colors', 'title'));
    }

}