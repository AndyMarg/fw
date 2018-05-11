<?php

namespace vendor\core;


use config\ConfigDB;

class Db
{
    private  $pdo;
    private static $instance;

    private static $countQueries;
    private static $queries = [];

    private function __construct()
    {
        // настройки PDO
        $connect_options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,      // выдавать в поток вывода ошибки SQL
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC  // возвращать данные в виде ассоциативного (не нумерованного массива)
        ];
        // подключаемся к БД
        $this->pdo = new \PDO(ConfigDB::DSN, ConfigDB::USER, ConfigDB::PASS, $connect_options);
    }

    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function execute($sql) {
        self::$countQueries++;
        self::$queries[] = $sql;

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute();
    }

    public function query($sql) {
        self::$countQueries++;
        self::$queries[] = $sql;

        $stmt = $this->pdo->prepare($sql);
        if (false !== $stmt->execute()) {
            return $stmt->fetchAll();
        }
        return [];
    }

    public static function getCountQueries()
    {
        return self::$countQueries;
    }

    public static function getQueries()
    {
        return self::$queries;
    }


}