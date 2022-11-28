<?php

//Declaring namespace
namespace LaswitchTech\coreDB;

//Import phpRouter class into the global namespace
use LaswitchTech\phpRouter\phpRouter;
use LaswitchTech\phpAUTH\Auth;
use LaswitchTech\coreDB\coreDB;

class Router extends phpRouter {

  protected $Auth = null;
  protected $coreDB = null;
  protected $Settings = null;
  protected $Debug = false;

  public function __construct(){

    // Configure API
    $this->configure();

    // Initiate Auth
    $this->Auth = new Auth("SESSION");

    // Inititate Parent Constructor
    parent::__construct();
  }

  protected function configure(){

    // Save Root Path
    $this->Path = dirname(\Composer\Factory::getComposerFile());
    define("ROOT_PATH", $this->Path);

    // Main Auth Configuration Information
    define("AUTH_B_TYPE", "SQL");
    define("AUTH_RETURN", "BOOLEAN");
    define("AUTH_OUTPUT_TYPE", "STRING");

    // Include manifest configuration file
    if(is_file($this->Path . "/src/manifest.json")){

      // Save all settings
      $this->Manifest = json_decode(file_get_contents($this->Path . '/src/manifest.json'),true);

      // MySQL Debug
      if(isset($this->Manifest['sql']['debug'])){
        $this->Debug = $this->Manifest['sql']['debug'];
      }

      // Auth Configuration Information
      if(isset($this->Manifest['auth']['roles'])){
        define("AUTH_ROLES", $this->Manifest['auth']['roles']);
      } else {
        define("AUTH_ROLES", true);
      }
      if(isset($this->Manifest['auth']['groups'])){
        define("AUTH_GROUPS", $this->Manifest['auth']['groups']);
      } else {
        define("AUTH_GROUPS", false);
      }
      if(isset($this->Manifest['auth']['type']['application'])){
        define("AUTH_F_TYPE", $this->Manifest['auth']['type']['application']);
      } else {
        define("AUTH_F_TYPE", "SESSION");
      }

      // Router Configuration Information
      if(isset($this->Manifest['router'])){
        if(isset($this->Manifest['router']['requirements'])){
          define("ROUTER_REQUIREMENTS", $this->Manifest['router']['requirements']);
        }
        if(isset($this->Manifest['router']['routes'])){
          define("ROUTER_ROUTES", $this->Manifest['router']['routes']);
        }
      }

      // coreDB Configuration Information
      if(isset($this->Manifest['coreDB'])){
        if(isset($this->Manifest['coreDB']['brand'])){
          define("COREDB_BRAND", $this->Manifest['coreDB']['brand']);
        }
        if(isset($this->Manifest['coreDB']['breadcrumbs']['type'])){
          define("COREDB_BREADCRUMBS_TYPE", $this->Manifest['coreDB']['breadcrumbs']['type']);
        }
        if(isset($this->Manifest['coreDB']['breadcrumbs']['count'])){
          define("COREDB_BREADCRUMBS_COUNT", $this->Manifest['coreDB']['breadcrumbs']['count']);
        }
        if(isset($this->Manifest['coreDB']['icons'])){
          define("COREDB_ICONS", $this->Manifest['coreDB']['icons']);
        }
        if(isset($this->Manifest['coreDB']['navbar'])){
          //
          $menu = [];
          foreach(ROUTER_ROUTES as $route => $details){
            $item = ["route" => $route,"label" => $route];
            if(isset($details['label'])){ $item['label'] = $details['label']; }
            $menu[] = $item;
          }
          $this->Manifest['coreDB']['navbar']['*'] = [
            ["label" => "Debug", "icon" => "bug", "menu" => $menu],
          ];
          //
          define("COREDB_NAVBAR", $this->Manifest['coreDB']['navbar']);
        }
        if(isset($this->Manifest['coreDB']['sidebar'])){
          define("COREDB_SIDEBAR", $this->Manifest['coreDB']['sidebar']);
        }
      }
    } else {

      // Auth Configuration Information
      define("AUTH_ROLES", true);
      define("AUTH_GROUPS", false);
      define("AUTH_F_TYPE", "SESSION");
    }

    // Include main configuration file
    if(is_file($this->Path . "/config/config.json")){

      // Save all settings
    	$this->Settings = json_decode(file_get_contents($this->Path . '/config/config.json'),true);

      //MySQL Configuration Information
      define("DB_HOST", $this->Settings['sql']['host']);
      define("DB_USERNAME", $this->Settings['sql']['username']);
      define("DB_PASSWORD", $this->Settings['sql']['password']);
      define("DB_DATABASE_NAME", $this->Settings['sql']['database']);

      // MySQL Debug
      if(isset($this->Settings['sql']['debug'])){
        $this->Debug = $this->Settings['sql']['debug'];
      }
    } else {

      // Could not find settings
      header_remove('Set-Cookie');
      header('HTTP/1.1 500 Internal Server Error');
      exit;
    }

    // MySQL Debug
    define("DB_DEBUG", $this->Debug);
  }

  public function isRoute($route){ return ($route == $this->Route); }

  public function render(){
    $this->coreDB = new coreDB($this->Route,$this->Routes);
    return parent::render();
  }
}
