<?php

// dev mode engaged
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('ROOT_PATH', dirname(__DIR__));

// Register the autoloader
require_once '../core/AutoLoader.php';
$autoLoader = new core\AutoLoader(ROOT_PATH);
spl_autoload_register(array($autoLoader, 'autoLoad'));

// let's do this
$app = new core\App();
$app->launch();
