<?php

namespace app\controllers;

use vendor\core\base\Controller;

class Main extends Controller
{
    public function indexAction() {
        debug(__METHOD__, 'Вызов');
    }

}