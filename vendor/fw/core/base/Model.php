<?php

namespace fw\core\base;


use fw\core\Db;
use Valitron\Validator;

abstract class Model
{
    private $db;
    private $table;
    private $pk = 'id';

    private $attributes = [];
    private $errors = [];
    private $rules = [];

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

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function setRules($rules)
    {
        $this->rules = $rules;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function query($sql)
    {
        return $this->db->execute($sql);
    }

    public function load(array $data)
    {
        foreach ($this->attributes as $key => $value) {
            if (isset($data[$key])) {
                $this->attributes[$key] = $data[$key];
            }
        }
    }

    public function save($table)
    {
        $user = \R::dispense($table);
        foreach ($this->attributes as $name => $value) {
            $user->$name = $value;
        }
        return \R::store($user);
    }

    public function validate()
    {
        $validator = new Validator($this->attributes);
        $validator->rules($this->rules);
        if ($validator->validate()) {
            return true;
        } else {
            $this->errors = $validator->errors();
            // сохраняем HTML код ошибок в сессии
            $messages = '<ul>';
            foreach ($this->errors as $error) {
                foreach ($error as $item) {
                    $messages .= "<li>$item</li>";
                }
            }
            $messages  .= '</ul>';
            $_SESSION['errors'] = $messages;
            return false;
        }
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