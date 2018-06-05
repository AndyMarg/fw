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
        if (trim($login) && trim($password)) {
            $data = $this->findByName('login', $login);
            debug($data); die;
        }
        return false;
    }

}