<?php

namespace app\controllers;

use app\models\Main;
use fw\core\base\View;
use fw\core\Config;
use fw\core\Pagination;

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

        // настройка пагинации
        $totalRows = $this->model->count();
        $rowsPerPage = Config::instance()->pagination->rows_per_page;
        $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $pagination = new Pagination($currentPage, $rowsPerPage, $totalRows);

        // получаем данные из базы
        $posts = $this->model->findAll([$pagination->getFirstRowInPage(), $rowsPerPage]);

        $this->setVars(compact('posts', 'pagination'));
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