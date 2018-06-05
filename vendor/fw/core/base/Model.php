<?php

namespace fw\core\base;


use fw\core\Db;
use Valitron\Validator;

/**
 * Class Model Базовый класс модели
 * @package fw\core\base
 */
abstract class Model
{
    private $db;            // менеждер БД
    private $table;         // таблица, связанная с моделью
    private $pk = 'id';     // поле первичного ключа (уникальный ИД объекта модели)

    private $attributes = [];   // аттрибуты модели, которые будут сохраняться в БД
    private $errors = [];       // ошибки (не пусто, если что-то пошло не так)
    private $rules = [];        // равила валидации (в формате библиотеки Valitron\Validator)
    private $uniquies = [];     // список аттрибутов, которые должны быть уникальными

    public function __construct()
    {
        $this->db = Db::instance();
    }

    /**************************************************************************
     *  Методы доступа
     **************************************************************************/

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

    public function setAttribute($key, $value) {
        if (key_exists($key,$this->attributes)) {
            $this->attributes[$key] = $value;
        }
    }

    public function getAttribute($key)
    {
        if (key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }
        return false;
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

    public function getUniquies()
    {
        return $this->uniquies;
    }

    public function setUniquies($unequies)
    {
        $this->uniquies = $unequies;
    }


    /**
     * Выполнение произвольного запроса к БД (без возврата результирующего массива)
     *
     * @param $sql  Собственно запрос
     * @param array $params Необязятельный массив параметров
     * @return mixed true? если успешно, иначе - false
     */
    public function query($sql, $params = [])
    {
        return $this->db->execute($sql, $params);
    }

    /**
     * Загрузка атрибутов модель
     * Устанавливаются только те аттрибуты, которые "прописаны" в свойстве attributes
     *
     * @param array $data Массив аттрибутов для загрузки.
     */
    public function load(array $data)
    {
        foreach ($this->attributes as $key => $value) {
            if (isset($data[$key])) {
                $this->attributes[$key] = $data[$key];
            }
        }
    }

    /**
     * Сохранение аттрибутов в бд
     *
     * @return mixed true, если успешно
     */
    public function save()
    {
        $sql = 'insert into ' . $this->table .  '('. implode(',',  array_keys($this->attributes)) . ')' .
               ' values (' . substr(str_repeat('?,', sizeof($this->attributes)), 0, sizeof($this->attributes)*2-1)  . ')';
        return $this->db->execute($sql, array_values($this->attributes));
    }

    /**
     * Сформировать HTML код для вывода ошибок
     *
     * @return string HTML код
     */
    private function getHtmlCodeForErrors()
    {
        $messages = '<ul>';
        foreach ($this->errors as $error) {
            foreach ($error as $item) {
                $messages .= "<li>$item</li>";
            }
        }
        $messages  .= '</ul>';
        return @$messages;
    }

    /**
     * Проверка уникальности аттрибутов (уникальные аттрибуты находяться в массиве uniquies
     *
     * @return bool false, если нарушена уникальность
     */
    private function checkUniquies()
    {
        $result = true;
        foreach ($this->uniquies as $field => $format) {
            if ($this->findByName($field, $this->attributes[$field])) {
                $this->errors[$field][0] = sprintf($format,  $this->attributes[$field]);
                $result = false;
            }
        }
        return $result;
    }

    /**
     * Валидация модели
     *
     * @return bool true, если успешно
     */
    public function validate()
    {
        $validator = new Validator($this->attributes);
        $validator->rules($this->rules);
        if ($validator->validate()) {
            if (!$this->checkUniquies())  {
                $_SESSION['errors'] = $this->getHtmlCodeForErrors();
                $_SESSION['form_data'] =  $this->attributes;
                return false;
            }

            return true;
        } else {
            $this->errors = $validator->errors();
            $_SESSION['errors'] = $this->getHtmlCodeForErrors();
            $_SESSION['form_data'] =  $this->attributes;
            return false;
        }
    }

    /**
     * Получить все данные из БД
     *
     * @return mixed Результирующий массив данных из БД
     */
    public function findAll()
    {
        $sql = "select * from {$this->table}";
        return $this->db->query($sql);
    }

    /**
     * Вернуть данные для объекта по ИД
     *
     * @param $id ИД
     * @return mixed Массив данных из БД
     */
    public function findById($id)
    {
        $sql = "select * from {$this->table} where {$this->pk} = ?";
        return $this->db->query($sql, [$id]);
    }

    /**
     * Вернуть массив данных из БД по значению поля
     *
     * @param $field Имя поля
     * @param $value Значение пол Количество возвращаемых строк (если -1 - возвращаем все строки)
     * @return mixed Массив возвращаемых данных
     */
    public function findByName($field, $value, $numRows = -1)
    {
        $limit = (-1 === $numRows) ? "" : " limit $numRows";
        $sql = "select * from {$this->table} where $field  = ? ?";
        return $this->db->query($sql, [$value, $limit]);
    }

    /**
     * Возврат данных из БД для произвольного запроса
     *
     * @param $sql Собственно запрос
     * @param array $params Необязв\ательные параметры
     * @return mixed Массив возврашаемых данных
     */
    public function findBySql($sql, $params = [])
    {
        return $this->db->query($sql, $params);
    }

    /**
     * Возвращаем данные из БД с использованием LIKE для значения поля
     *
     * @param $pattern Паттерн
     * @param $field Поле
     * @return mixed Массив возвращаемых данных
     */
    public function findByLike($pattern, $field)
    {
        $sql = "select * from {$this->table} where $field  like ?";
        return $this->db->query($sql, ['%' .$pattern . '%']);
    }

}