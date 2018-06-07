<?php

namespace fw\core;

/**
 * Class Pagination Класс пагинации
 * @package fw\core
 */
class Pagination
{
    private $currentPage;   // текущая страница
    private $rowsPerPage;   // строк на странице
    private $totalRows;     // общее количесво строк
    private $totalPages;    // количество страниц
    private $uri;           // базовый uri

    public function __construct($Page, $rowsPerPage, $totalRows)
    {
         $this->rowsPerPage = $rowsPerPage;
         $this->totalRows = $totalRows;
         $this->totalPages = $this->getCountPages();
         $this->currentPage = $this->getCurrentPage($Page);
         $this->uri = $this->getParams();
    }

    public function getTotalRows()
    {
        return $this->totalRows;
    }

    public function getTotalPages()
    {
        return $this->totalPages;
    }

    /**
     * @return int Количество страниц
     */
    private function getCountPages()
    {
        return  ceil($this->totalRows / $this->rowsPerPage) ?: 1;
    }

    /**
     * @param $page Запрошенная страница
     * @return int Действительная страница
     */
    private function getCurrentPage($page)
    {
        if (!$page || $page < 1)  $page = 1;
        if ($page > $this->totalPages) $page = $this->totalPages;
        return $page;
    }

    /**
     * @return int Номер первой записи на текущей страницы
     */
    public function getFirstRowInPage()
    {
        return ($this->currentPage - 1) * $this->rowsPerPage;
    }

    /**
     * @return string Строка параметров без параметров типа page=n
     */
    private function getParams()
    {
        $url_parts = explode('?', $_SERVER['REQUEST_URI']);
        $uri = $url_parts[0] . '?';
        if (isset($url_parts[1]) && $url_parts[1] !== '') {
            $params = explode('&', $url_parts[1]);
            foreach ($params as $param) {
                if (!preg_match('#page=#', $param)) $uri .= "{$param}&amp;";
            }
        }
        return $uri;
    }

    /**
     * @return string Код HTML для объекта пагинации
     */
    private function getHtml() {

        $back = null;           // ссылка на предыдущую страницу
        $forward = null;        // ссылка на следующую страницу
        $startpage = null;      // ссылка на первую страницу
        $endpage = null;        // ссылка на последнюю страницу
        $page2left = null;      // вторая страница слева
        $page1left = null;      // первая страница слева
        $page2right = null;     // вторая страница справа
        $page1right = null;     // первая страница справа

        if ($this->currentPage > 1)
            $back = "<li><a class='nav-link' href='{$this->uri}page=" . ($this->currentPage - 1) . "'>&lt;</a></li>";
        if ($this->currentPage < $this->totalPages)
            $forward = "<li><a class='nav-link' href='{$this->uri}page=" . ($this->currentPage + 1) . "'>&gt;</a></li>";
        if ($this->currentPage > 3)
            $startpage = "<li><a class='nav-link' href='{$this->uri}page=1'>&laquo;</a></li>";
        if ($this->currentPage <  ($this->totalPages - 2))
            $endpage = "<li><a class='nav-link' href='{$this->uri}page={$this->totalPages}'>&raquo;</a></li>";
        if (($this->currentPage - 2) > 0 )
            $page2left = "<li><a class='nav-link' href='{$this->uri}page=" . ($this->currentPage - 2) . "'>" . ($this->currentPage - 2) . "</a></li>";
        if (($this->currentPage - 1) > 0 )
            $page1left = "<li><a class='nav-link' href='{$this->uri}page=" . ($this->currentPage - 1) . "'>" . ($this->currentPage - 1) . "</a></li>";
        if (($this->currentPage + 1) <= $this->totalPages )
            $page1right = "<li><a class='nav-link' href='{$this->uri}page=" . ($this->currentPage + 1) . "'>" . ($this->currentPage + 1) . "</a></li>";
        if (($this->currentPage + 2) <= $this->totalPages )
            $page2right = "<li><a class='nav-link' href='{$this->uri}page=" . ($this->currentPage + 2) . "'>" . ($this->currentPage + 2) . "</a></li>";

        return
            '<ul class="pagination">' .
                $startpage.$back.$page2left.$page1left .
                '<li class="active"><a>' . $this->currentPage . '</a></li>' .
                $page1right.$page2right.$forward.$endpage .
            '</ul>'  ;
    }

    /**
     * @return string Строковое представление объекта
     */
    public function __toString()
    {
        return $this->getHtml();
    }
}
