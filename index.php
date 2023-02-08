<?php

//Import Router class into the global namespace
use LaswitchTech\coreDB\Router;

define('ROUTER_ROOT',__DIR__);

if(!is_file(__DIR__ . '/webroot/index.php')){

  //Load Composer's autoloader
  require ROUTER_ROOT . "/vendor/autoload.php";

  //Initiate phpRouter
  $Router = new Router();
}

require __DIR__ . '/webroot/index.php';
