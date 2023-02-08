<?php

//Initiate Session
session_start();

//Import API class into the global namespace
use LaswitchTech\coreDB\API;

// Define Root Path
define('ROOT_PATH',__DIR__);

//Load Composer's autoloader
require 'vendor/autoload.php';

//Initiate API
new API();
