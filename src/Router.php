<?php

//Declaring namespace
namespace LaswitchTech\coreDB;

//Import phpRouter class into the global namespace
use LaswitchTech\phpRouter\phpRouter;

//Import coreDB, Auth and Configurator class into the global namespace
use LaswitchTech\coreDB\coreDB;
use LaswitchTech\coreDB\Configurator;
use LaswitchTech\coreDB\Auth;

class Router extends phpRouter {

  protected $Auth = null;
  protected $coreDB = null;
  protected $Configurator = null;
  protected $Path = null;
  protected $Debug = false;

  public function __construct(){

    // Configure Router
    $this->Configurator = new Configurator();

    // Initiate Auth
    $this->Auth = new Auth("SESSION");

    // Inititate Parent Constructor
    parent::__construct();
  }

  public function isRoute($route){ return ($route == $this->Route); }

  public function render(){
    $this->coreDB = new coreDB($this->Route,$this->Routes, $this->Configurator,$this->Auth);
    return parent::render();
  }

  protected function getDist(){
    $directories = $this->scandir('dist','directory');
    foreach($directories as $key => $directory){
      if(in_array($directory,['psd'])){ unset($directories[$key]); }
    }
    return $directories;
  }

  protected function getIndex(){
    $index = '';
    $index .= '<?php' . PHP_EOL;
    $index .= PHP_EOL;
    $index .= '// Initiate Session' . PHP_EOL;
    $index .= 'session_start();' . PHP_EOL;
    $index .= PHP_EOL;
    $index .= '// Import Router class into the global namespace' . PHP_EOL;
    $index .= 'use LaswitchTech\coreDB\Router;' . PHP_EOL;
    $index .= PHP_EOL;
    $index .= '// Define Root Path' . PHP_EOL;
    $index .= 'if(!defined("ROOT_PATH")){' . PHP_EOL;
    $index .= '  define("ROOT_PATH",dirname(__DIR__));' . PHP_EOL;
    $index .= '}' . PHP_EOL;
    $index .= PHP_EOL;
    $index .= '// Load Composer\'s autoloader' . PHP_EOL;
    $index .= 'require ROOT_PATH . "/vendor/autoload.php";' . PHP_EOL;
    $index .= PHP_EOL;
    $index .= '// Initiate Router' . PHP_EOL;
    $index .= '$Router = new Router();' . PHP_EOL;
    $index .= PHP_EOL;
    $index .= '// Render Request' . PHP_EOL;
    $index .= '$Router->render();' . PHP_EOL;

    return $index;
  }
}
