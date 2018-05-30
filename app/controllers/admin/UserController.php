<?php

namespace app\controllers\admin;

use fw\core\base\View;

class UserController extends AppController
{
    public function indexAction()
    {
        View::setMeta('Администрирование');
    }

    public function testAction()
    {
    }
}

