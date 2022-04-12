<?php
session_start();

// Set Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Import Librairies
require_once dirname(__FILE__).'/src/lib/request.php';

// Start Request
new Request();
