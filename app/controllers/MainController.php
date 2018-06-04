<?php

namespace app\controllers;

use app\models\Main;
use fw\core\base\View;
use fw\core\Config;

class MainController extends AppController
{
    private $model;

    public function __construct($route)
    {
        parent::__construct($route);
        $this->model = new Main();
    }

    public function indexAction()
    {
        View::setMeta('Главная страница', 'Описание страницы', 'Ключевые слова');

        // пример работы с кэшем !!!!!!!!!!!!

        // пробуем получить данные из кэша
        $posts = Config::instance()->getCache()->get('posts');
        // если данных в кэше нет (либо еще не созданы, либо кэш удален по причине устаревания)
        if (!$posts) {
            // получаем данные из базы
            $posts = $this->model->findAll();
            // соэраняем в кэше на 1 минуту
            Config::instance()->getCache()->set('posts', $posts, 60);
        }

        $this->setVars(compact('posts'));
    }

    public function testAction()
    {
        if($this->isAjax()) {
            $post = \R::findOne('posts', "id = {$_POST['id']}");
            echo View::getViewContent($this, 'a_test', compact('post'));
            die;
        }
        $this->setLayout('test');
    }

}