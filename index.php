<?php

//Import Router class into the global namespace
use LaswitchTech\coreDB\Router;

// Define Root Path
define('ROOT_PATH',__DIR__);

if(!is_file(__DIR__ . '/webroot/index.php')){

  //Load Composer's autoloader
  require ROOT_PATH . "/vendor/autoload.php";

  //Initiate phpRouter
  $Router = new Router();
}

require __DIR__ . '/webroot/index.php';
