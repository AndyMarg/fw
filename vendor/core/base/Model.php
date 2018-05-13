<?php

namespace vendor\core\base;


use vendor\core\Db;

abstract class Model
{
    private $db;
    private $table;
    private $pk = 'id';

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

    public function getPk()
    {
        return $this->pk;
    }

    public function setPk($pk)
    {
        $this->pk = $pk;
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

    public function findById($id)
    {
        $sql = "select * from {$this->table} where {$this->pk} = ?";
        return $this->db->query($sql, [$id]);
    }

    public function findByName($field, $value, $numRows = -1)
    {
        $limit = (-1 === $numRows) ? "" : " limit $numRows";
        $sql = "select * from {$this->table} where $field  = ? ?";
        return $this->db->query($sql, [$value, $limit]);
    }

    public function findBySql($sql, $params = [])
    {
        return $this->db->query($sql, $params);
    }

    public function findByLike($pattern, $field)
    {
        $sql = "select * from {$this->table} where $field  like ?";
        return $this->db->query($sql, ['%' .$pattern . '%']);
    }

}