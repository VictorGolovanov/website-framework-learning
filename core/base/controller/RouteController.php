<?php

namespace core\base\controller;

/**
 * Main Route Controller
 *
 * This class handles the routing functionality and processing of the URL in a web application.
 * It is responsible for determining the appropriate controller, input method, and output method based on the URL.
 * The class follows the MVC architecture and supports both the user and admin sections of the application.
 * It also handles plugins by dynamically loading their routes and settings if the URL corresponds to a plugin route.
 * The class uses the Settings class to retrieve the application's route configurations.
 * If the URL doesn't match any defined routes, it throws an exception.
 * 
 * @author victor
 */

use core\base\settings\Settings;
use core\base\settings\ShopSettings;


class RouteController {
    
    private static $_instance;
    
    protected $routes;
    
    protected $controller;
    protected $inputMethod;
    protected $outputMethod;
    protected $parameters;


    private function __clone() {
        //
    }
    
    // singleton
    public static function getInstance() {
        if (self::$_instance instanceof self) {
            return self::$_instance;
        }
        return self::$_instance = new self;
    }
    
    private function __construct() {

        // work with adress line
        
        $adress_str = $_SERVER['REQUEST_URI'];
        
        // redirect
        if (strrpos($adress_str, '/') === strlen($adress_str) - 1 && strrpos($adress_str, '/') !== 0) {
            $this->redirect(rtrim($adress_str, '/'), 301);
        }
        
        $path = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'] , 'index.php'));
        
        if ($path === PATH) {
            $this->routes = Settings::get('routes');
            
            if (!$this->routes) {
                throw new RouteException('Something went wrong');
            }
            
            $url = explode('/', substr($adress_str, strlen(PATH)));
            
            if ($url[0] && $url[0] === $this->routes['admin']['alias']) {
                array_shift($url);
                
                // plugins
                if ($url[0] && $this->isDir($url)) {
                    $plugin = array_shift($url);    
                    $pluginSettings = $this->routes['settings']['path'] . ucfirst($plugin) . 'Settings';

                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . PATH . $pluginSettings . '.php')) {
                        $pluginSettings = str_replace('/', '\\', $pluginSettings);
                        $this->routes = $pluginSettings::get('routes');
                    }
                    
                    $dir = $this->routes['plugins']['dir'] ? '/' . $this->routes['plugins']['dir'] . '/' : '/';
                    $dir = str_replace('//', '/', $dir);
                    
                    $this->controller = $this->routes['plugins']['path'] . $plugin . $dir;
                    $hrUrl = $this->routes['plugins']['hrUrl'];
                    $route = 'plugins';
                    
                } else {
                    $this->controller = $this->routes['admin']['path'];
                    $hrUrl = $this->routes['admin']['hrUrl'];
                    $route = 'admin';
                }
                
            } else {
//                $url = explode('/', substr($adress_str, strlen(PATH)));
                $hrUrl = $this->routes['user']['hrUrl'];
                $this->controller = $this->routes['user']['path'];
                
                $route = 'user';
            }
            
            $this->createRoute($route, $url);
            
            if ($url[1]) {
                $count = count($url);
                $key = '';
                
                if (!$hrUrl) {
                    $i = 1;
                } else {
                    $this->parameters['alias'] = $url[1];
                    $i = 2;
                }
                
                for (; $i < $count; $i++) {
                    if (!$key && array_key_exists($i, $url)) {
                        $key = $url[$i];
                        $this->parameters[$key] = '';
                    } else {
                        $this->parameters[$key] = $url[$i];
                        $key = '';
                    }
                }
            }
            var_dump(printArr($this));
        } else {
            try {
                throw \Exception('wrong directory');
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }   
    }
    
    private function createRoute($var, $arr) {
        $route = [];
        
        if (!empty($arr[0])) {
            if ($this->routes[$var]['routes'][$arr[0]]) {
                $route = explode('/', $this->routes[$var]['routes'][$arr[0]]);
                $this->controller .= ucfirst($route[0].'Controller');
            } else {
                $this->controller .= ucfirst($arr[0].'Controller');
            }
        } else {
            $this->controller .= $this->routes['default']['controller'];
        }
        
        $this->inputMethod = $route[1] ? $route[1] : $this->routes['default']['inputMethod'];
        $this->outputMethod = $route[2] ? $route[2] : $this->routes['default']['outputMethod'];
        
        return;
    }
    
    private function isDir($url) {
        return is_dir($_SERVER['DOCUMENT_ROOT'] . PATH . $this->routes['plugins']['path'] . $url[0]);
    }
}
