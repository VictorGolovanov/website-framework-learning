<?php
// here we work on framework for making websites
define('VG_ACCESS', true);

//imports
use core\base\exceptions\RouteException;
use core\base\controller\RouteController;


header('Content-Type:text/html; charset=utf-8');
session_start();

require_once 'config.php';
require_once 'core/base/settings/internal_settings.php';
require_once 'libraries/functions.php';

echo "Hello!\n";

try {
    RouteController::getInstance()->route();
} catch (RouteException $e) {
    echo($e->getMessage());
}