<?php

//Import API class into the global namespace
//These must be at the top of your script, not inside a function
use LaswitchTech\phpRouter\phpRouter;

//Load Composer's autoloader
require 'vendor/autoload.php';

//Initiate phpRouter
$phpRouter = new phpRouter();

//Adding Static Routes
$phpRouter->add('/signin', __DIR__ . '/View/signin.php');

//Load Request
$phpRouter->load();
