<?php

namespace core\base\settings;
use core\base\settings\Settings;

/**
 * Class of Additional settings
 * Class for plugin settings for Shop plugin
 *
 * @author victor
 */
class ShopSettings {
    
    private static $_instance;
    
    private $baseSettings;

    private $routes = [
        'admin' => [
            'alias' => 'sudo',
        ],
        'test' => [
            'name' => 'test'
        ],
    ];

    private $templateArr = [
        'text' => ['price'],
        'textarea' => ['goods_content']
    ];
    
    private function __construct() {}
    private function __clone() {}
    
    public static function get($property) {
        return self::getInstance()->$property;
    }
    
    public static function getInstance() {
        if (self::$_instance instanceof self) {
            return self::$_instance;
        }
        
        self::$_instance = new self;
        self::$_instance->baseSettings = Settings::getInstance();
        $baseProperties = self::$_instance->baseSettings->clueProperties(get_class());
        self::$_instance->setProperties($baseProperties);
        
        return self::$_instance;
    }
    
    protected function setProperties($properties) {
        if ($properties) {
            foreach ($properties as $name => $property) {
                $this->$name = $property;
            }
        }
    }
    
}
