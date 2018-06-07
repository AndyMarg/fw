<?php

namespace fw\core;

/**
 * TEST
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
    private function getFirstRowInPage()
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
        return "This is a Pagination object";
    }

    /**
     * @return string Строковое представление объекта
     */
    public function __toString()
    {
        return $this->getHtml();
    }
}
