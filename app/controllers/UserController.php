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
            if (!$user->validate()) {
                redirect();
            }
            if ($user->save('user')) {
                $_SESSION['success'] = 'Вы успешно зарегистрированы!';
                // здесь может быть редирект на страницу профиля пользователя
                redirect();
            } else {
                $_SESSION['errors'] = 'Ошибка! Попробуйте позже';
                redirect();
            }
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
