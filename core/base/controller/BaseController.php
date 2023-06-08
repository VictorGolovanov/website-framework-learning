<?php

namespace core\base\controller;

/**
 * Description of BaseController
 *
 * @author victor
 */

use core\base\exceptions\RouteException;

abstract class BaseController {
    
    protected $page;
    protected $errors;

    protected $controller;
    protected $inputMethod;
    protected $outputMethod;
    protected $parameters;
    
    public function route() {
        $controller = str_replace('/', '\\', $this->controller);
        
        try {
            $object = new \ReflectionMethod($controller, 'request');
        
            $args = [
                'parameters' => $this->parameters,
                'inputMethod' => $this->inputMethod,
                'outputMethod' => $this->outputMethod,
            ];

            $object->invoke(new $controller, $args);
            
        } catch (\ReflectionException $e) {
            throw new RouteException($e->getMessage());
        }
    }
    
    public function request($args) {
        $this->parameters = $args['parameters'];

        $inputData = $args['inputMethod'];
        $outputData = $args['outputMethod'];

        if (method_exists($this, $inputData)) {
            $this->$inputData();
        } else {
            throw new RouteException("Method $inputData does not exist in " . get_class($this));
        }

        if (method_exists($this, $outputData)) {
            $this->page = $this->$outputData();
        } else {
            throw new RouteException("Method $outputData does not exist in " . get_class($this));
        }

        if ($this->errors) {
            $this->writeLog($this->errors);
        }
    }

    
    
    protected function render($path = '',$parameters = []) {
        extract($parameters);
        
        if (!$path) {
            $path = TEMPLATE . explode('controller', strtolower((new \ReflectionClass($this))->getShortName()))[0];
            var_dump("!!!path => $path");
        }
        
        ob_start();
        
        if (!include_once $path . '.php') {
            throw new RangeException("There is no tamplate $path");
        }
        
        return ob_get_clean();
        
    }


    protected function getPage() {
        exit($this->page);
    }
}
