<?php

// Front Controller

// 1. Common settings
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 2. System files connection
define('ROOT', dirname(__FILE__));
//require_once(ROOT.'/components/Router.php');
require_once(ROOT.'/components/Autoload.php');

// 3. Connection with DB

$GLOBALS['DBH'] = DB::getConnection();

// 4. Router call
$router = new Router();
$router->Run();
