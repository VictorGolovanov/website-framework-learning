<?php

namespace core\base\settings;

/**
 * Class of Main Settings
 *
 * @author victor
 */
class Settings {
    
    private static $_instance;

    private $routes = [
        'admin' => [
            'alias' => 'admin',
            'path' => 'core/admin/controller/',
            'hrUrl' => false,
        ],
        'settings' => [
            'path' => 'core/base/settings/'
        ],
        'plugins' => [
            'path' => 'core/plugins/',
            'hrUrl' => false
        ],
        'user' => [
            'path' => 'core/user/controller/',
            'hrUrl' => true,
            'routes' => [
                //controller/method-data-collection/method-data-output
                'catalog' => 'site/input/output'
            ]
        ],
        'default' => [
            'controller' => 'IndexController',
            'inputMethod' => 'inputData',
            'outputMethod' => 'outputData'
        ]
    ];
    
    private $templateArr = [
        'text' => ['name', 'phone', 'adress'],
        'textarea' => ['content', 'keywords']
    ];
    
    private function __construct() {}
    private function __clone() {}
    
    public static function getInstance() {
        if (self::$_instance instanceof self) {
            return self::$_instance;
        }
        return self::$_instance = new self;
    }
    
    public static function get($property) {
        return self::getInstance()->$property;
    }
    
    public function clueProperties($class) {
        $baseProperties =[];
        
        foreach ($this as $name => $item) {
            $property = $class::get($name);
            if (is_array($property) && is_array($item)) {
                $baseProperties[$name] = arrayMergeRecursive($this->$name, $property);
                continue;
            }
            
            if (!$property) {
                $baseProperties[$name] = $this->$name;
            }
        }
        return $baseProperties;
    }    
}
