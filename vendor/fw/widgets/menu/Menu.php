<?php

namespace vendor\fw\widgets\menu;


use vendor\fw\core\Config;

/**
 * Class Menu Виджет меню (настраиваемый)
 * @package vendor\widgets\menu
 */
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
     * @var Время кэширования
     */
    private $cache_time;
    /**
     * @var Ключ кэширования
     */
    private $cache_key;

    public function __construct() {
        // Задаем параметры конфигурации меню в виде массива
        $this->configure([
            'table' => Config::instance()->widgets->menu->table,
            'html_container' => Config::instance()->widgets->menu->html_container,
            'template' => Config::instance()->widgets->menu->template,
            'container_class' => Config::instance()->widgets->menu->container_class,
            'cache_time' =>  Config::instance()->widgets->menu->cache_time,
            'cache_key' =>  Config::instance()->widgets->menu->cache_key
        ]);
    }

    /**
     * Конфигурируем меню
     * Устанавливаем свойства объекта Menu (имена свойств должны совпадать с ключами массива конфигурации
     *
     * @param $options Массив с параметрами конфигурации меню
     */
    public function configure($options)
    {
        foreach ($options as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Формируем и выводим меню
     *
     * @throws \Exception В случае, если имя таблицы бд, из которой формируется меню не определено
     */
    public function run()
    {
        if (!isset($this->table) || $this->table === '')
            throw new \Exception('Не определена таблица для формирования меню');
        // Получаем меню из кэша
        $this->htmlCode = Config::instance()->getCache()->get($this->cache_key);
        // если из кэша достать не получилось (отсутствует или устарел), берем из бд
        if (!$this->htmlCode) {
            // исходный плоский массив из базы (в каждой строке ссылка на родителя. если есть)
            $this->dbDataArray = \R::getAssoc("select * from $this->table");
            // строим промежуточный массив дерева на основе исходного
            $this->treeArray = $this->getTree($this->dbDataArray);
            // получаем HTML код
            $this->htmlCode = $this->getHtmlCode($this->treeArray);
            // если определен ключч кэширования. то кэшируем
            if (!empty($this->cache_key)) {
                Config::instance()->getCache()->set($this->cache_key, $this->htmlCode, $this->cache_time);
            }
        }
        // выводим меню
        $this->show($this->htmlCode);
    }

    /**
     * Выводим меню
     *
     * @param $html HTML код меню
     */
    private function show( $html)
    {
        echo "<{$this->html_container} class='{$this->container_class}'>";
        echo $html;
        echo "</{$this->html_container}>";
    }

    /**
     * Строим иерархический массив в виде дерева
     *
     * @param $source Исходные данные из бд (плоский массив) (в каждой строке ссылка на родителя. если есть)
     * @return array Массив в виде дерева
     */
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

    /**
     * Строим HTML код меню (вызывается рекурсивно из HTML шаблона)
     *
     * @param $treeNode  Узел дерева
     * @param string $tab  Строка для сдвига узла дерева на очередном уровне
     * @return string HTML код меню
     */
    private function getHtmlCode ($treeNode, $tab = '')
    {
        $html ='';
        foreach ( $treeNode as $id => $node) {
            // добавляем HTML код для очередного пункта меню
            $html .= $this->getHtmlForCurrentNode($node, $tab, $id);
        }
        return $html;
    }

    /**
     * Строим HTML код для очередного пункта меню
     *
     * @param $node Очередной пункт меню
     * @param $tab Строка для сдвига узла дерева на очередном уровне
     * @param $id ИД пункта меню (ИД из таблицы бд)
     * @return string HTML код пункта меню
     */
    private function getHtmlForCurrentNode($node, $tab, $id)
    {
        ob_start();
        // выводим HTML код из шаблона в буфер вывода
        require $this->template;
        return ob_get_clean();
    }

}