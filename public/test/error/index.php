<?php

define('DEBUG', 1);
define('E_EXCEPTION', 100000);
define('FILE_LOGGING', 0);

class ErrorHandler
{
    public function __construct()
    {
        if (DEBUG) {
            error_reporting(-1);
        } else {
            error_reporting(0);
        }
        set_error_handler([$this, 'errorHandler']);
        ob_start();
        register_shutdown_function([$this, 'fatalErrorHandler']);
        set_exception_handler([$this, 'exceptionHandler']);
    }

    private function getErrorLevelDescript ($errno)
    {
        $errors = array(
            E_ERROR => 'E_ERROR',
            E_WARNING => 'E_WARNING',
            E_PARSE => 'E_PARSE',
            E_NOTICE => 'E_NOTICE',
            E_CORE_ERROR => 'E_CORE_ERROR',
            E_CORE_WARNING => 'E_CORE_WARNING',
            E_COMPILE_ERROR => 'E_COMPILE_ERROR',
            E_COMPILE_WARNING => 'E_COMPILE_WARNING',
            E_USER_ERROR => 'E_USER_ERROR',
            E_USER_WARNING => 'E_USER_WARNING',
            E_USER_NOTICE => 'E_USER_NOTICE',
            E_STRICT => 'E_STRICT',
            E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
            E_DEPRECATED => 'E_DEPRECATED',
            E_USER_DEPRECATED => 'E_USER_DEPRECATED',
            E_EXCEPTION => 'E_EXCEPTION'
        );
        return $errors[$errno];
    }

    private function log($errno, $errstr, $errfile, $errline) {
        $errLevel = $this->getErrorLevelDescript($errno);
        error_log("[" . date('Y-m-d H-i-s') . "] Уровень: {$errLevel} |  Ошибка: {$errstr} | " .
            "Файл: {$errfile} | Строка: {$errline}\n", 3, __DIR__ . '/errors.log');
    }

    public function errorHandler($errno, $errstr, $errfile, $errline)
    {
        if (FILE_LOGGING)
            $this->log($errno, $errstr, $errfile, $errline);
        $this->displayError($errno, $errstr, $errfile, $errline);
        return true;
    }

    public function exceptionHandler($e)
    {
        $responseCode = empty($e->getCode()) ? 500 : $e->getCode();
        if (FILE_LOGGING)
            $this->log(E_EXCEPTION, $e->getMessage(), $e->getFile(), $e->getLine());
        $this->displayError(E_EXCEPTION, $e->getMessage(), $e->getFile(), $e->getLine(), $responseCode);
    }

    public function fatalErrorHandler()
    {
        $error = error_get_last();
        if (!empty($error) && $error['type'] & ( E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR)) {
            ob_end_clean();
            if (FILE_LOGGING)
                $this->log($error['type'], $error['message'], $error['file'], $error['line']);
            $this->displayError($error['type'], $error['message'], $error['file'], $error['line']);
        } else {
            ob_end_flush();
        }
    }

    private function displayError($errno, $errstr, $errfile, $errline, $responseCode = 500)
    {
        http_response_code($responseCode);
        $errLevel = $this->getErrorLevelDescript($errno);
        if (DEBUG) {
            require_once 'views/dev.php';
        } else {
            require_once 'views/prod.php';
        }
    }

 }


new ErrorHandler();

//echo $test;
test();
//throw new Exception('Упс, исключение...', 404);