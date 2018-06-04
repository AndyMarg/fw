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
    }

}