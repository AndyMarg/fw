<?php

/**
 * Class Controller Родитель всех контроллеров
 */
abstract class Controller
{
    public function __construct()
    {
        echo __METHOD__;
    }

    // метод по умолчанию для любого контроллера
    public function index() {
          
    }

}