<?php
/**
 * Created by PhpStorm.
 * User: Andrey.Margashov
 * Date: 10.05.2018
 * Time: 11:25
 */

namespace app\controllers;


use vendor\core\base\Controller;

class Page extends Controller
{
    public function viewAction() {
        debug(__METHOD__, 'Вызов');
        debug($this->getRoute(),'Current rout');
        debug($_GET, 'GET parameters');
    }

}