<?php

namespace app\controllers;

use vendor\core\base\Controller;

class Posts extends Controller
{
    public function indexAction() {
        debug(__METHOD__, 'Вызов');
    }

    public function testAction() {
        debug(__METHOD__, 'Вызов');
    }

}