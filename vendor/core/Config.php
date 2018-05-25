<?php

namespace vendor\core;

require_once 'TSingleton.php';

/**
 * Class Config Конфигурация фреймворка (синглетон)
 * Доступ ко многим свойствам - через магические методы к массиву $registry.
 * Подмассивы в $registry представлены как объекты класса ArrayAsObject,
 * то есть доступ к элементам подмассивов возможен как к свойствам
 */
class Config
{
    use TSingleton;

    private $registry = [];             // массив для хранения конфигурации и доступа к элементам как к свойствам
    private $objectRegistry;            // реестр заранее созданных объектов
    private $_root = 'UNDEFINED';       // путь к корню приложения
    private $cache;                     // менеджер кэша
    private $errorHandler;              // обработчик ошибок
    private $config_source;             // объединенный исходный массив конфигурации (пользовательский и системной)

    private function __construct() {}

    /**
     * Рекурсивный метод для добавления подмассивов (элементов) к $registry
     *
     * @param array $parent   Родительский элемен
     * @param array $child    Дочерний подмассив
     * @return mixed          Объект типа ArrayAsObject
     */
    private function addArrayToRegistry($parent, $child)
    {
        foreach ($child as $name => $value) {
            if(is_array($value)) {
                $parent[$name] = [];
                $next_array_as_object = $this->addArrayToRegistry($parent[$name], $value);
                $parent[$name] = new ArrayAsObject($next_array_as_object);
            } else {
                $parent[$name]  = $value;
            }
        }
        return $parent;
    }

    /**
     * Удаляет из массивов конфигурации дублирующиеся значения,
     * если пользовательская конфигурация перезаписывает конфигурацию фреймворка.
     * Приоритет отдается пользовательской конфигурации
     *
     * @param $arr Объединенный массив конфигурации (передается по ссылке!!!!)
     */
    private function removeDuplicates(&$arr) {
        if (is_array($arr)) {
            foreach ($arr as $key => $value) {
                if (is_array($value)) {
                    if (!empty($value)) {
                        if (0 === array_keys($value)[0]) {
                            $arr[$key] = $value[0];
                        } else {
                            $this->removeDuplicates($value);
                            $arr[$key] = $value;
                        }
                    }
                }
            }
        }
    }

    /**
     * Инициализация фреймворка (должен быть первым методом из фреймворка вызываемом в приложении)
     *
     * @param array $app_config_data  Массив пользовательской конфигурации
     */
    public function init(array $app_config_data) {        // устанавливаем путь к корню приложения (для работы функции автозагрузки классов)
        $this->_root = ROOT;

        // функция автозагрузки
        spl_autoload_register(function ($class) {
            $file = $this->getRoot() . '/' . str_replace('\\', '/', $class) . '.php';
            if(is_file($file)) {
                require_once $file;
            }
        });

        // массив системной конфигурации
        require_once 'config_data.php';
        // объединяем пользовательскую и системную конфингурацию
        $all_config_data = array_merge_recursive($app_config_data, $_config_data);
        $this->removeDuplicates($all_config_data);
        // сохраняем в приватной переменной для дальнейшей отладки
        $this->config_source = $all_config_data;

       // формируем реестр конфигурации
        $this->registry = $this->addArrayToRegistry($this->registry, $all_config_data);

        // создаем обработчика ошибок
        $this->errorHandler = new ErrorHandler();

        // создаем реестр загружаемых объектов и заполняем его на основе конфигурации
        $this->objectRegistry = ObjectRegistry::instance();
        if (isset($this->objects)) {
            $this->objectRegistry->addFromArray($this->objects->getData());
        }

        // создаем менеджер кэша
        if (isset($this->objectRegistry->cache)) {
            $this->cache = $this->objectRegistry->cache;
        }
    }

    /**
     * "Магический метод". Вызывается при получении неизвестного свойства.
     * Возвращает массив настроек раздела конфигурации, если существует, иначе ошибка.
     *
     * @param string $name Имя раздела конфигурации
     * @return mixed|null Объект
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->registry)) {
            return $this->registry[$name];
        }
        trigger_error("Not such item in configuration: \"$name\"", E_USER_NOTICE);
        return null;
    }

    /**
     * "Магический метод". Вызывается при проверке существования свойства
     * @param string $name Ключ массива
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->registry[$name]);
    }

    /**
     * @return string Путь к корню приложения
     */
    public function getRoot()
    {
        return $this->_root;
    }

    /**
     * @return array Реестр конфигурации
     */
    public function getRegistry() {
        return $this->registry;
    }

    /**
     * @return mixed Реестр загруженных объектов
     */
    public function getObjectRegistry()
    {
        return $this->objectRegistry;
    }

    /**
     * @return mixed Возвращает менеджер кэша
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @return array объединенный исходный массив конфигурации (пользовательский и системной)
     */
    public function getConfigSource()
    {
        return $this->config_source;
    }
}