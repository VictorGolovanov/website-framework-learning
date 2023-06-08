<?php

namespace core\user\controller;

/**
 * Description of IndexController
 *
 * @author victor
 */

use core\base\controller\BaseController;

class IndexController extends BaseController{
    
    protected function hello() {
        var_dump("Hello method hello() is called!");
        $template = $this->render('', ['name' => 'Felix']);
        exit($template);
    }
}
