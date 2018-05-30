<?php

namespace vendor\fw\core;

/**
 * Class ErrorHandler Обработчик ошибок
 * @package vendor\fw\core
 *
 */
class ErrorHandler
{
    const E_EXCEPTION = 100000;  // уровень ошибок для исключений. Используется в getErrorLevelDescript()

    public function __construct()
    {
        // обрабатывать ли ошибки
        if (Config::instance()->debug->debugging) {
            error_reporting(-1);
        } else {
            error_reporting(0);
        }
        // обработчик обычных ошибок
        set_error_handler([$this, 'errorHandler']);
        // для отмкны вывода системы - буферизируем вывод
        ob_start();
        // обработчик фатальных ошибок
        register_shutdown_function([$this, 'fatalErrorHandler']);
        // обработчик исключений (пользовательских и системных). Перехватывает использование errorHandler
        set_exception_handler([$this, 'exceptionHandler']);
    }

    /**
     * Для вывода контанты уровня ошибки
     *
     * @param $errno
     * @return mixed Уровень ошибки
     */
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
            self::E_EXCEPTION => 'E_EXCEPTION'  // определена как константа класса
        );
        return $errors[$errno];
    }

    /**
     * Вывод ошибки в лог. Файл лога определяется в пользовательской конфигурации (debug->logfile).
     * Если файл лога не определен, вывод в лог не происходит.
     *
     * @param $errno    Уровень ошибки
     * @param $errstr   Сообщени
     * @param $errfile  Файл, в котором произошла ошибка
     * @param $errline  Строка, в которой произошла ошибка
     */
    private function log($errno, $errstr, $errfile, $errline) {
        $logfile = Config::instance()->path->error_log . '/' . Config::instance()->debug->logfile;
        if(empty($logfile)) return;
        $errLevel = $this->getErrorLevelDescript($errno);
        error_log("[" . date('Y-m-d H-i-s') . "] Уровень: {$errLevel} |  Ошибка: {$errstr} | " .
            "Файл: {$errfile} | Строка: {$errline}\n", 3, $logfile);
    }

    /**
     * Обработчик ошибок
     *
     * @param $errno    Уровень ошибки
     * @param $errstr   Сообщени
     * @param $errfile  Файл, в котором произошла ошибка
     * @param $errline  Строка, в которой произошла ошибка
     * @return bool     Если true - ошибка обработана
     */
    public function errorHandler($errno, $errstr, $errfile, $errline)
    {
        if (Config::instance()->debug->logging)
            $this->log($errno, $errstr, $errfile, $errline);
        if (Config::instance()->debug->debugging || in_array($errno, [E_USER_ERROR, E_RECOVERABLE_ERROR]))
            $this->displayError($errno, $errstr, $errfile, $errline);
        return true;
    }

    /**
     * Обработчик исключений
     *
     * @param $e Класс исключения
     */
    public function exceptionHandler($e)
    {
        $responseCode = empty($e->getCode()) ? 500 : $e->getCode();
        if (Config::instance()->debug->logging)
            $this->log(self::E_EXCEPTION, $e->getMessage(), $e->getFile(), $e->getLine());
        $this->displayError(self::E_EXCEPTION, $e->getMessage(), $e->getFile(), $e->getLine(), $responseCode, $e->getTraceAsString());
    }

    /**
     * Обработчик фатальных ошибок
     */
    public function fatalErrorHandler()
    {
        $error = error_get_last();
        if (!empty($error) && $error['type'] & ( E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR)) {
            // отменяем буферизацию вывода, включенную в кострукторе для отмены вывода сообщения системой
            ob_end_clean();
            if (Config::instance()->debug->logging)
                $this->log($error['type'], $error['message'], $error['file'], $error['line']);
            $this->displayError($error['type'], $error['message'], $error['file'], $error['line']);
        } else {
            ob_end_flush();
        }
    }

    /**
     * Вывод ошибки в поток вывода (http response по умолчанию)
     *
     * @param $errno    Уровень ошибки
     * @param $errstr   Сообщени
     * @param $errfile  Файл, в котором произошла ошибка
     * @param $errline  Строка, в которой произошла ошибка
     * @param int $responseCode Код ответа сервера (по умолчанию 500)
     */
    private function displayError($errno, $errstr, $errfile, $errline, $responseCode = 500, $trace = '')
    {
        $error_path = Config::instance()->path->error_views;
        http_response_code($responseCode);
        // 404
        if ($responseCode === 404) {
            include $error_path . '/' . Config::instance()->debug->view_404;
            die;
        }
        // прочие ошибки
        $errLevel = $this->getErrorLevelDescript($errno);
        if (Config::instance()->debug->debugging) {
            require_once $error_path . '/' . Config::instance()->debug->dev_error_view;
        } else {
            require_once $error_path . '/' . Config::instance()->debug->prod_error_view;
        }
        die;
    }

}