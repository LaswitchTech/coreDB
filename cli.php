<?php
session_start();

// Import Librairies
require_once dirname(__FILE__).'/src/lib/command.php';

if((defined('STDIN'))&&(!empty($argv[1]))){
  // Start Command
  new Command($argv);
}

// foreach($cmds as $key => $args){
//   $method = str_replace('-','_',$key);
//   if(method_exists($API,$method)){
//     $response = $API->$method($args);
// 		if(($response != null)&&($response != "null")){ echo json_encode($response, JSON_PRETTY_PRINT); }
//   } else { echo "{'error': 'Unknow Function'}"; exit; }
// }
