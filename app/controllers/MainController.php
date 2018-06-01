<?php

namespace app\controllers;

use fw\core\base\View;
use fw\core\Config;
//use Monolog\Handler\StreamHandler;
//use Monolog\Logger;

class MainController extends AppController
{
    public function __construct($route)
    {
        parent::__construct($route);
    }

    public function indexAction()
    {
        View::setMeta('Главная страница', 'Описание страницы', 'Ключевые слова');

        // Пример работы с пакетом установленным Composer - пакет Monolog для логгирования
        /*
        // create a log channel
        $log = new Logger('name');
        $log->pushHandler(new StreamHandler(Config::instance()->path->temp . '/test.log', Logger::WARNING));
        // add records to the log
        $log->warning('Foo');
        $log->error('Bar');
        */

        // пример работы с кэшем !!!!!!!!!!!!

        // пробуем получить данные из кэша
        $posts = Config::instance()->getCache()->get('posts');
        // если данных в кэше нет (либо еще не созданы, либо кэш удален по причине устаревания)
        if (!$posts) {
            // получаем данные из базы
            $posts = \R::findAll('posts');
            // соэраняем в кэше на 1 минуту
            Config::instance()->getCache()->set('posts', $posts, 60);
        }

        $menu = $this->getMenu();
        $this->setVars(compact('posts', 'menu'));
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