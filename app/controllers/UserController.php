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
            // если не прошли валидацию
            if (!$user->validate()) {
                redirect();
            // иначе
            } else {
                // Хэшируем пароль
                $user->setAttribute('password', password_hash($user->getAttribute('password'), PASSWORD_DEFAULT));
                // сохраняем информацию о пользователе в бд
                if ($user->save()) {
                    $_SESSION['success'] = 'Вы успешно зарегистрированы!';
                    // здесь может быть редирект на страницу профиля пользователя
                    redirect();
                } else {
                    $_SESSION['errors'] = 'Ошибка! Попробуйте позже';
                    redirect();
                }
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
