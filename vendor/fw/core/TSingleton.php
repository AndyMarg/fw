<?php

namespace vendor\fw\core;

/**
 * Trait TSingleton Для добавления к классам шаблона синглетон
 * @package vendor\fw\core
 */
trait TSingleton
{
    private static $instance;

    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }
}