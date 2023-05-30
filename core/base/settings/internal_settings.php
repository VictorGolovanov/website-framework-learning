<?php
defined('VG_ACCESS') or die('Access denied!');

// imports
use core\base\exceptions\RouteException;


// templates
const ADMIN_TEMPLATE = 'core/admin/view/';
const TEMPLATE = 'templates/default/';

//security
const COOKIE_VERSION = '1.0.0';
const CRYPT_KEY = '';
const COOKIE_TIME = 60;
const BLOCK_TIME = 3;

// page navigation
const QTY = 8;
const QTY_LINKS = 3;

// front-end
const ADMIN_CSS_JS = [
    'styles' => [],
    'scripts' => []
];
const USER_CSS_JS = [
    'styles' => [],
    'scripts' => []
];

// class loader
spl_autoload_register(function ($className) {
    // convert class name to file path
    $filePath = str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';

    // check if file exists
    if (!@include_once $filePath) {
        throw new RouteException("Wrong class name: $className");
    }
});
