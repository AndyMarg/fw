<?php

namespace app\models;


use fw\core\base\Model;

class Main extends Model
{

    public function __construct()
    {
        parent::__construct();
        $this->setTable('posts');
    }
}