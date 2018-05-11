<?php

namespace vendor\core\base;


use vendor\core\Db;

class Model
{
    private $db;
    private $table;

    public function __construct()
    {
        $this->db = Db::instance();
    }

    public function getTable()
    {
        return $this->table;
    }

    public function setTable($table)
    {
        $this->table = $table;
    }

    public function query($sql)
    {
        return $this->db->execute($sql);
    }

    public function findAll()
    {
        $sql = "select * from {$this->table}";
        return $this->db->query($sql);
    }

}