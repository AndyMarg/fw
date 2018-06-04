<?php

/**
 * Вывод отлабочной информации
 *
 * @param $var Переменная/объект для вывода
 * @param string $message Поясняющее сообщение
 */
function debug($var,  $message = '')
{
    if ($message) {
        echo "<b style='text-decoration: underline'>$message</b><br>";
    }
    echo '<pre>';
    if (empty($var)) {
        echo "(empty)";
    } else {
        echo print_r($var, true);
    }
    echo '</pre>';
}

/**
 * редирект на заданную страницу
 *
 * @param $url URL для редиректа. Если не задан - перенаправляем на ту страницу, с которой пользователь пришел.
 */
function redirect($url = false) {
    if ($url) {
        $redirect = $url;
    } else {
        $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';
    }
    header("Location: $redirect");
    // завершаем выполнение скрипта
    exit;
}
