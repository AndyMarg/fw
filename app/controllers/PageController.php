<?php

namespace app\controllers;

class PageController extends AppController
{
    public function __construct($route)
    {
        parent::__construct($route);
        $this->setLayout('about');
    }

    public function viewAction() {
    }

    public function aboutAction() {

    }

}