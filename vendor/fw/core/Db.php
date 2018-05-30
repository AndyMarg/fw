<?php

namespace vendor\fw\core;


class Db
{
    use TSingleton;

    private  $pdo;

    private static $countQueries;
    private static $queries = [];

    private function __construct()
    {
        $config = Config::instance();
        require_once $config->root . '/vendor/fw/libs/rb-mysql.php';
        \R::setup($config->db->dns, $config->db->user, $config->db->pass);
        \R::freeze(true);
        //\R::fancyDebug(true);

//        // настройки PDO
//        $connect_options = [
//            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,      // выдавать в поток вывода ошибки SQL
//            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC  // возвращать данные в виде ассоциативного (не нумерованного массива)
//        ];
//        // подключаемся к БД
//        $this->pdo = new \PDO(ConfigDB::DSN, ConfigDB::USER, ConfigDB::PASS, $connect_options);

    }

    public function execute($sql, $params = [])  {
        self::$countQueries++;
        self::$queries[] = $sql;

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function query($sql, $params = []) {
        self::$countQueries++;
        self::$queries[] = $sql;

        $stmt = $this->pdo->prepare($sql);
        if (false !== $stmt->execute($params)) {
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