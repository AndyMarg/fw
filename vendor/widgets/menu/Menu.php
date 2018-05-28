<?php

namespace vendor\widgets\menu;


use vendor\core\Config;

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
    private $template;
    /**
     * @var string Родительский тэг для меню
     */
    private $html_container;
    /**
     * @var Класс Родительского тега для меню
     */
    private $container_class;
    /**
     * @var Таблица БД с данными для построения меню
     */
    private $table;
    /**
     * @var Объект для кэширования
     */
    private $cache;

    public function __construct() {
        $this->configure([
            'table' => Config::instance()->widgets->menu->table,
            'html_container' => Config::instance()->widgets->menu->html_container,
            'template' => Config::instance()->widgets->menu->template,
            'container_class' => Config::instance()->widgets->menu->container_class,
            'cache' => 3600
        ]);
    }

    public function configure($options)
    {
        foreach ($options as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function run()
    {
        if (!isset($this->table) || $this->table === '')
            throw new \Exception('Не определена таблица для формирования меню');
        $this->dbDataArray = \R::getAssoc("select * from $this->table");
        $this->treeArray = $this->getTree($this->dbDataArray);
        $this->htmlCode = $this->getHtmlCode($this->treeArray);
        $this->show($this->htmlCode);
    }

    private function show(string $html)
    {
        echo "<{$this->html_container} class='{$this->container_class}'>";
        echo $html;
        echo "</{$this->html_container}>";
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
        $html ='';
        foreach ( $treeNode as $id => $node) {
            $html .= $this->catToTemplate($node, $tab, $id);
        }
        return $html;
    }

    private function catToTemplate($node, $tab, $id)
    {
        ob_start();
        require $this->template;
        return ob_get_clean();
    }

}