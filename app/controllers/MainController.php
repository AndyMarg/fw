<?php

namespace app\controllers;

use app\models\Main;

class MainController extends AppController
{
    public function __construct($route)
    {
        parent::__construct($route);
        //$this->setLayout('main');
    }

    public function indexAction()
    {
        // мета-данные возьмем из первого поста (для примера)
//        $meta = \R::findOne('posts','id = 1');
//        $this->setMeta($meta->title, $meta->description, $meta->keywords);

        $this->setMeta('Главная страница', 'Описание страницы', 'Ключевые слова');

        $model = new Main();
        $posts = \R::findAll('posts');
        $menu = $this->getMenu();
        $this->setVars(compact('posts', 'menu'));
    }

    public function testAction()
    {
        $this->setLayout('test');
    }

}