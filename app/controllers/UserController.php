<?php

namespace app\controllers;


use app\models\User;
use fw\core\base\View;

class UserController extends AppController
{
    public function signupAction()
    {
        if (!empty($_POST)) {
            $user = new User();
            $user->load($_POST);
            if ($user->validate()) {
                echo 'OK';
            } else {
                debug($user->getErrors(),'Error validation');
            }
            die;
        }
        View::setMeta('Регистрация');
    }

    public function loginAction()
    {

    }

    public function logoutAction()
    {

    }

}
