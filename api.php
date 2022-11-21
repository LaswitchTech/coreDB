<?php

//Import API class into the global namespace
//These must be at the top of your script, not inside a function
use LaswitchTech\phpAPI\phpAPI;

//Load Composer's autoloader
require 'vendor/autoload.php';

//Load Constants
require 'src/constants.php';
define("AUTH_F_TYPE", "BEARER");
define("AUTH_RETURN", "HEADER");
define("AUTH_OUTPUT_TYPE", "HEADER");

//Initiate API
new phpAPI();
