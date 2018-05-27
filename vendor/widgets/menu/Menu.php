<?php

namespace vendor\widgets\menu;


class Menu
{
    /**
     * @var array Массив данных из базы (ключ - id, значение - ассоциативный массив столбцов)
     */
    private $dbDataArray;
    /**
     * @var array  Многомерный массив данных из базы (ключ - id, значение - запись из базы или дочерний массив записей
     */
    private $treeArray;
    /**
     * @var HTML код меню
     */
    private $htmlCode;
    /**
     * @var string Путь к HTML шаблону
     */
    private $tempale;
    /**
     * @var string Родительский тэг для меню
     */
    private $container;
    /**
     * @var Таблица БД с данными для построения меню
     */
    private $table;
    /**
     * @var Объект для кэширования
     */
    private $cache;

    public function __construct() {
        $this->run();
    }

    private function run()
    {
        $this->dbDataArray = \R::getAssoc("select * from categories");
        $this->treeArray = $this->getTree($this->dbDataArray);
        $this->htmlCode = $this->getHtmlCode($this->treeArray);
    }

    private function getTree($source)
    {
        $tree = [];
        foreach ($source as $id=>&$node) {
            if (!$node['parent']){
                $tree[$id] = &$node;
            }else{
                $source[$node['parent']]['childs'][$id] = &$node;
            }
        }
        return $tree;
    }

    private function getHtmlCode ($treeNode, $tab = '')
    {
        $str ='';
        foreach ( $treeNode as $id => $node) {
            $this->catToTemplate($node, $tab, $id);
        }
        return $str;
    }

    private function catToTemplate($node, $tab, $id)
    {
        ob_start();
        
        return ob_get_clean();
    }



}