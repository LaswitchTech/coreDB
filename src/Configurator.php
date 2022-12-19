<?php

//Declaring namespace
namespace LaswitchTech\coreDB;

//Import Factory class into the global namespace
use Composer\Factory;

class Configurator {

  protected $Path = null;
  protected $Debug = false;
  protected $Settings = null;
  protected $Manifest = null;
  protected $Protocol = null;
  protected $Domain = null;
  protected $URL = null;
  protected $PHPVersion = null;

  public function __construct(){

    // Configure PHP
    ini_set('memory_limit', '2G');
    ini_set('max_execution_time', 0);
    // ini_set('ignore_repeated_errors', 1);
    // ini_set("display_errors", 0);
    // ini_set("log_errors", 1);
    // ini_set("error_log", dirname(__FILE__,3) . "/tmp/error.log");

    // Gathering Server Information
    $this->PHPVersion=substr(phpversion(),0,3);

    // Setup Protocol
		$this->Protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")."://";

    // Setup Domain
		if(isset($_SERVER['HTTP_HOST'])){
			$this->Domain = $_SERVER['HTTP_HOST'];
		}

    // Setup URL
    $this->URL = $this->Protocol.$this->Domain.'/';

    // Setup Root Path
    $this->Path = dirname(\Composer\Factory::getComposerFile());
    if(!defined("ROOT_PATH")){ define("ROOT_PATH", $this->Path); }

    // Main Auth Configuration Information
    if(!defined("AUTH_B_TYPE")){ define("AUTH_B_TYPE", "SQL"); }
    if(!defined("AUTH_RETURN")){ define("AUTH_RETURN", "BOOLEAN"); }
    if(!defined("AUTH_OUTPUT_TYPE")){ define("AUTH_OUTPUT_TYPE", "STRING"); }

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
        if(!defined("AUTH_ROLES")){ define("AUTH_ROLES", $this->Manifest['auth']['roles']); }
      } else {
        if(!defined("AUTH_ROLES")){ define("AUTH_ROLES", true); }
      }
      if(isset($this->Manifest['auth']['groups'])){
        if(!defined("AUTH_GROUPS")){ define("AUTH_GROUPS", $this->Manifest['auth']['groups']); }
      } else {
        if(!defined("AUTH_GROUPS")){ define("AUTH_GROUPS", false); }
      }
      if(isset($this->Manifest['auth']['type']['application'])){
        if(!defined("AUTH_F_TYPE")){ define("AUTH_F_TYPE", $this->Manifest['auth']['type']['application']); }
      } else {
        if(!defined("AUTH_F_TYPE")){ define("AUTH_F_TYPE", "SESSION"); }
      }

      // Router Configuration Information
      if(isset($this->Manifest['router'])){
        if(isset($this->Manifest['router']['requirements'])){
          if(!defined("ROUTER_REQUIREMENTS")){ define("ROUTER_REQUIREMENTS", $this->Manifest['router']['requirements']); }
        }
        if(isset($this->Manifest['router']['routes'])){
          if(!defined("ROUTER_ROUTES")){ define("ROUTER_ROUTES", $this->Manifest['router']['routes']); }
        }
      }

      // Include main configuration file
      if(is_file($this->Path . "/config/config.json")){

        // Save all settings
      	$this->Settings = json_decode(file_get_contents($this->Path . '/config/config.json'),true);

        // MySQL Configuration Information
        if(isset($this->Settings['sql'])){
          if(!defined("DB_HOST")){ define("DB_HOST", $this->Settings['sql']['host']); }
          if(!defined("DB_USERNAME")){ define("DB_USERNAME", $this->Settings['sql']['username']); }
          if(!defined("DB_PASSWORD")){ define("DB_PASSWORD", $this->Settings['sql']['password']); }
          if(!defined("DB_DATABASE_NAME")){ define("DB_DATABASE_NAME", $this->Settings['sql']['database']); }

          // MySQL Debug
          if(isset($this->Settings['sql']['debug'])){
            $this->Debug = $this->Settings['sql']['debug'];
          }
        }

        // SMTP Configuration Information
        if(isset($this->Settings['smtp'])){
          if(!defined("SMTP_HOST")){ define("SMTP_HOST", $this->Settings['smtp']['host']); }
          if(!defined("SMTP_PORT")){ define("SMTP_PORT", $this->Settings['smtp']['port']); }
          if(!defined("SMTP_ENCRYPTION")){ define("SMTP_ENCRYPTION", $this->Settings['smtp']['encryption']); }
          if(!defined("SMTP_USERNAME")){ define("SMTP_USERNAME", $this->Settings['smtp']['username']); }
          if(!defined("SMTP_PASSWORD")){ define("SMTP_PASSWORD", $this->Settings['smtp']['password']); }
        }
        if(!defined("SMTP_BRAND") && defined("COREDB_BRAND")){ define("SMTP_BRAND",COREDB_BRAND); }
        if(!defined("SMTP_LOGO") && defined("COREDB_LOGO")){ define("SMTP_LOGO",COREDB_LOGO); }
        if(!defined("SMTP_TRADEMARK") && defined("COREDB_TRADEMARK")){ define("SMTP_TRADEMARK",COREDB_TRADEMARK); }
        if(!defined("SMTP_POLICY") && defined("COREDB_POLICY")){ define("SMTP_POLICY",COREDB_POLICY); }
        if(!defined("SMTP_SUPPORT") && defined("COREDB_SUPPORT")){ define("SMTP_SUPPORT",COREDB_SUPPORT); }
        if(!defined("SMTP_CONTACT") && defined("COREDB_CONTACT")){ define("SMTP_CONTACT",COREDB_CONTACT); }

        // Saved URL
        if(isset($this->Settings['url'])){
          $this->URL = $this->Settings['url'];
        }
      }

      // coreDB Configuration Information
      if(isset($this->Manifest['coreDB'])){
        if(isset($this->Manifest['coreDB']['brand'])){
          if(!defined("COREDB_BRAND")){ define("COREDB_BRAND", $this->Manifest['coreDB']['brand']); }
        }
        if(isset($this->Manifest['coreDB']['breadcrumbs']['type'])){
          if(!defined("COREDB_BREADCRUMBS_TYPE")){ define("COREDB_BREADCRUMBS_TYPE", $this->Manifest['coreDB']['breadcrumbs']['type']); }
        }
        if(isset($this->Manifest['coreDB']['breadcrumbs']['count'])){
          if(!defined("COREDB_BREADCRUMBS_COUNT")){ define("COREDB_BREADCRUMBS_COUNT", $this->Manifest['coreDB']['breadcrumbs']['count']); }
        }
        if(isset($this->Manifest['coreDB']['icons'])){
          if(!defined("COREDB_ICONS")){ define("COREDB_ICONS", $this->Manifest['coreDB']['icons']); }
        }
        if(isset($this->Manifest['coreDB']['navbar'])){
          $menu = [];
          if(defined("ROUTER_ROUTES")){
            foreach(ROUTER_ROUTES as $route => $details){
              $item = ["route" => $route,"label" => $route];
              if(isset($details['label'])){ $item['label'] = $details['label']; }
              $menu[] = $item;
            }
          }
          $this->Manifest['coreDB']['navbar']['*'] = [
            ["label" => "Debug", "icon" => "bug", "menu" => $menu],
          ];
          if(!defined("COREDB_NAVBAR")){ define("COREDB_NAVBAR", $this->Manifest['coreDB']['navbar']); }
        }
        if(isset($this->Manifest['coreDB']['sidebar'])){
          if(!defined("COREDB_SIDEBAR")){ define("COREDB_SIDEBAR", $this->Manifest['coreDB']['sidebar']); }
        }
        if(isset($this->Manifest['coreDB']['logo'])){
          if(!defined("COREDB_LOGO")){ define("COREDB_LOGO",$this->Manifest['coreDB']['logo']); }
        }
        if(isset($this->Manifest['coreDB']['trademark'])){
          if(!defined("COREDB_TRADEMARK")){ define("COREDB_TRADEMARK",$this->Manifest['coreDB']['trademark']); }
        }
        if(isset($this->Manifest['coreDB']['policy'])){
          if(!defined("COREDB_POLICY")){ define("COREDB_POLICY",$this->Manifest['coreDB']['policy']); }
        }
        if(isset($this->Manifest['coreDB']['support'])){
          if(!defined("COREDB_SUPPORT")){ define("COREDB_SUPPORT",$this->Manifest['coreDB']['support']); }
        }
        if(isset($this->Manifest['coreDB']['contact'])){
          if(!defined("COREDB_CONTACT")){ define("COREDB_CONTACT",$this->Manifest['coreDB']['contact']); }
        }
      }
    }

    // Auth Configuration Information
    if(!defined("AUTH_ROLES")){ define("AUTH_ROLES", true); }
    if(!defined("AUTH_GROUPS")){ define("AUTH_GROUPS", false); }
    if(!defined("AUTH_F_TYPE")){ define("AUTH_F_TYPE", "SESSION"); }

    // coreDB Configuration Information
    if($this->URL != null && $this->URL != 'http:///'){
      $this->configure(['url' => $this->URL]);
    }
    if(!defined("COREDB_URL")){ define("COREDB_URL",$this->URL); }
    if(!defined("COREDB_LOGO")){ define("COREDB_LOGO",COREDB_URL . "dist/img/logo.png"); }
    if(!defined("COREDB_TRADEMARK")){ define("COREDB_TRADEMARK",COREDB_URL . "trademark"); }
    if(!defined("COREDB_POLICY")){ define("COREDB_POLICY",COREDB_URL . "policy"); }
    if(!defined("COREDB_SUPPORT")){ define("COREDB_SUPPORT",COREDB_URL . "support"); }
    if(!defined("COREDB_CONTACT")){ define("COREDB_CONTACT",COREDB_URL . "contact"); }

    // MySQL Debug
    if(!defined("DB_DEBUG")){ define("DB_DEBUG", $this->Debug); }
  }

  protected function configure($array = []){
    try {
      $config = [];
      $this->mkdir('config');
      if(is_file($this->Path . '/config/config.json')){
        $config = json_decode(file_get_contents($this->Path . '/config/config.json'),true);
      }
      foreach($array as $key => $value){ $config[$key] = $value; }
      $json = fopen($this->Path . '/config/config.json', 'w');
      fwrite($json, json_encode($config, JSON_PRETTY_PRINT));
      fclose($json);
      return true;
    } catch(Exception $error){
      return false;
    }
  }

  protected function mkdir($directory){
    $make = $this->Path;
    $directories = explode('/',$directory);
    foreach($directories as $subdirectory){
      $make .= '/'.$subdirectory;
      if(!is_file($make)&&!is_dir($make)){ mkdir($make, 0777, true); }
    }
    return $make;
  }
}
