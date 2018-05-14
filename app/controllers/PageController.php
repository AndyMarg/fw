<?php

namespace app\controllers;

use app\models\Main;

class PageController extends AppController
{
    public function viewAction() {
        $model = new Main();
        $menu = $this->getMenu();
        $this->setVars(compact('menu'));
    }

}