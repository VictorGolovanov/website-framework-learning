<?php

namespace core\base\settings;

/**
 * Class of Main Settings
 *
 * This class contains the main settings of the application, including routes, templates, and other properties.
 * It provides access to these settings and offers methods for merging properties with other classes.
 * The class uses the Singleton pattern to ensure that there is only one instance of it.
 * It stores routes for the admin section, user section, and plugins.
 * The class also contains an array of templates that define text and textarea fields for forms.
 * Its methods allow retrieving property values and merging them with other classes.
 *
 * @property array $routes Application routes
 * @property array $templateArr Array of field templates
 * 
 * @see core\base\settings\Settings::getInstance()
 * @see core\base\settings\Settings::get()
 * @see core\base\settings\Settings::clueProperties()
 * @see libraries\functions::arrayMergeRecursive()
 *
 *  @author victor
 */

class Settings {
    
    private static $_instance;

    private $routes = [
        'admin' => [
            'alias' => 'admin',
            'path' => 'core/admin/controller/',
            'hrUrl' => false,
            'routes' => [
                
            ],
        ],
        'settings' => [
            'path' => 'core/base/settings/'
        ],
        'plugins' => [
            'path' => 'core/plugins/',
            'hrUrl' => false,
            'dir' => false
        ],
        'user' => [
            'path' => 'core/user/controller/',
            'hrUrl' => true,
            'routes' => [
                //controller/method-data-collection/method-data-output
                'catalog' => 'site/hello/bye'
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
