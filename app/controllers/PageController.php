<?php

namespace app\controllers;

use app\models\Main;

class PageController extends AppController
{
    public function viewAction() {
        $model = new Main();
        $menu = $this->getMenu();
        $meta = $this->getMeta();
        $this->setVars(compact('meta', 'menu'));
    }

}