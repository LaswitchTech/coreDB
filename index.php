<?php
//Initiate Session
session_start();

//Import API class into the global namespace
//These must be at the top of your script, not inside a function
use LaswitchTech\coreDB\Router;

//Load Composer's autoloader
require 'vendor/autoload.php';

//Load Constants
require 'src/constants.php';
define("AUTH_F_TYPE", "SESSION");
define("AUTH_RETURN", "BOOLEAN");
define("AUTH_OUTPUT_TYPE", "STRING");

//Load Configurations
require 'config/config.php';

//Initiate phpRouter
$Router = new Router();

//Diagnostics
// if(isset($_SESSION)){ var_dump($_SESSION); }
// if(isset($_POST)){ var_dump($_POST); }

//Load Request
$file = $Router->render();
