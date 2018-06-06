<?php

namespace app\models;


use fw\core\base\Model;

class User extends Model
{

    public function __construct()
    {
        parent::__construct();
        $this->setTable('user');

        $this->setAttributes([
            'login' => '',
            'password' => '',
            'name' => '',
            'email' => ''
        ]);

        $this->setRules([
            'required' => [
                ['login'],
                ['password'],
                ['name'],
                ['email']
            ],
            'email' => [
                ['email']
             ],
            'lengthMin' => [
                ['password', 6]
            ]
        ]);

        $this->setUniquies([
            'login' => "Логин со значением \"%s\" уже зарегистрирован",
            'email' => "Email со значением \"%s\" уже зарегистрирован"
        ]);
    }

    public function login($login, $password)
    {
        if ($login && $password) {
            // получаем пользователя из БД
            $user = $this->findByName('login', $login);
            // проверяем пароль, если авторизация успешна, мохраняем информацию о пользователе в сессии
            if ($user && password_verify($password, $user[0]['password'])) {
                foreach ($user[0] as $name => $value) {
                    $_SESSION['user'][$name] = $value;
                }
                return true;
            }
        }
        return false;
    }

}