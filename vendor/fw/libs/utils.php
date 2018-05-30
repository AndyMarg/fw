<?php

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
