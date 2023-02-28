<?php

//Declaring namespace
namespace LaswitchTech\coreDB;

//Import phpEncryption's phpEncryption Class into the global namespace
use LaswitchTech\phpEncryption\phpEncryption;

class Configurator {

  protected $Path = null;
  protected $Debug = false;
  protected $Dev = true;
  protected $Maintenance = false;
  protected $DataDir = 'data';
  protected $Settings = null;
  protected $Manifest = null;
  protected $Protocol = null;
  protected $Domain = null;
  protected $URL = null;
  protected $PHPVersion = null;
  public $Encryption = null;

  public function __construct(){

    // Configure PHP
    ini_set('memory_limit', '4G');
    ini_set('max_execution_time', 0);
    // ini_set('ignore_repeated_errors', 1);
    // ini_set("display_errors", 0);
    // ini_set("log_errors", 1);
    // ini_set("error_log", dirname(__FILE__,3) . "/tmp/error.log");

    // Configure Timezone
    date_default_timezone_set('America/Toronto');

    // Configure Cookie Scope
    if(session_status() < 2){
      ini_set('session.cookie_samesite', 'Strict');
      ini_set('session.cookie_secure', 'On');
    }

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
    if(!defined("ROOT_PATH")){ define("ROOT_PATH",dirname(__DIR__)); }
    $this->Path = ROOT_PATH;

    // Initiate Encryption
    $this->Encryption = new phpEncryption();

    // Load Configurations
    $this->load();
  }

  public function getPath(){ return $this->Path; }

  public function getDataDir(){ return $this->DataDir; }

  public function getDebug(){ return $this->Debug; }

  public function load(){

    // Main Auth Configuration Information
    if(!defined("AUTH_B_TYPE")){ define("AUTH_B_TYPE", "SQL"); }
    if($_SERVER['SCRIPT_NAME'] == '/webroot/api.php'){
      if(isset($_SESSION) && !empty($_SESSION)){
        if(!defined("AUTH_TYPE")){ define("AUTH_TYPE", "SESSION"); }
      }
      if(!defined("AUTH_TYPE")){ define("AUTH_TYPE", "BEARER"); }
      if(!defined("AUTH_RETURN")){ define("AUTH_RETURN", "HEADER"); }
      if(!defined("AUTH_OUTPUT_TYPE")){ define("AUTH_OUTPUT_TYPE", "HEADER"); }
    }
    if(!defined("AUTH_RETURN")){ define("AUTH_RETURN", "BOOLEAN"); }
    if(!defined("AUTH_OUTPUT_TYPE")){ define("AUTH_OUTPUT_TYPE", "STRING"); }

    // Import Routes
    $routes = $this->Path . '/config/routes.json';
    if(is_file($routes)){
      $routes = json_decode(file_get_contents($routes),true);
      if(!defined('ROUTER_ROUTES')){
        define('ROUTER_ROUTES',$routes);
      }
    }

    // Include main configuration file
    if(is_file($this->Path . "/config/config.json")){

      // Retrieve all settings
      $this->Settings = $this->configurations();

      // Authorized Hosts
      if(!defined("AUTH_DOMAINS") && isset($this->Settings['domains'])){ define("AUTH_DOMAINS",$this->Settings['domains']); }
      if(!defined("AUTH_DOMAINS")){ define("AUTH_DOMAINS",[]); }

      // Setup Domain
      if($this->Domain == null && count(AUTH_DOMAINS) > 0){
        if(array_key_first(AUTH_DOMAINS) != ''){
          $this->Domain = AUTH_DOMAINS[array_key_first(AUTH_DOMAINS)];
        } else {
          $this->Domain = AUTH_DOMAINS[0];
        }
      }
      $this->URL = $this->Protocol.$this->Domain.'/';

      // MySQL Configuration Information
      if(isset($this->Settings['sql'])){

        // MySQL Constants
        if(!defined("DB_HOST")){ define("DB_HOST", $this->Settings['sql']['host']); }
        if(!defined("DB_USERNAME")){ define("DB_USERNAME", $this->Settings['sql']['username']); }
        if(!defined("DB_PASSWORD")){ define("DB_PASSWORD", $this->Settings['sql']['password']); }
        if(!defined("DB_DATABASE_NAME")){ define("DB_DATABASE_NAME", $this->Settings['sql']['database']); }

        // MySQL Debug
        if(isset($this->Settings['sql']['debug'])){
          $this->Debug = $this->Settings['sql']['debug'];
          if(!defined("DB_DEBUG")){ define("DB_DEBUG", $this->Settings['sql']['debug']); }
        }
      }

      // SMTP Configuration Information
      if(isset($this->Settings['smtp'])){

        // MySQL Constants
        if(!defined("SMTP_HOST")){ define("SMTP_HOST", $this->Settings['smtp']['host']); }
        if(!defined("SMTP_PORT")){ define("SMTP_PORT", $this->Settings['smtp']['port']); }
        if(!defined("SMTP_ENCRYPTION")){ define("SMTP_ENCRYPTION", $this->Settings['smtp']['encryption']); }
        if(!defined("SMTP_USERNAME")){ define("SMTP_USERNAME", $this->Settings['smtp']['username']); }
        if(!defined("SMTP_PASSWORD")){ define("SMTP_PASSWORD", $this->Settings['smtp']['password']); }
      }

      // Debug
      if(isset($this->Settings['debug'])){
        $this->Debug = $this->Settings['debug'];
      }

      // Maintenance
      if(isset($this->Settings['maintenance'])){
        $this->Maintenance = $this->Settings['maintenance'];
      }
    }

    // Include manifest configuration file
    if(is_file($this->Path . "/config/manifest.json")){

      // Save all settings
      $this->Manifest = json_decode(file_get_contents($this->Path . '/config/manifest.json'),true);

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
        if(isset($this->Manifest['coreDB']['navbar'])){
          if($this->Dev){
            $menu = [];
            if(defined("ROUTER_ROUTES")){
              foreach(ROUTER_ROUTES as $route => $details){
                $item = ["route" => $route,"label" => $route];
                if(isset($details['label'])){ $item['label'] = $details['label']; }
                if(isset($details['icon'])){ $item['icon'] = $details['icon']; }
                $menu[] = $item;
              }
            }
            $this->Manifest['coreDB']['navbar']['*'] = [
              ["label" => "Debug", "icon" => "bug", "menu" => $menu],
            ];
          }
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
        if(isset($this->Manifest['coreDB']['data'])){
          if(isset($this->Settings['data'])){
            $this->DataDir = $this->Settings['data'];
          } else {
            $this->DataDir = $this->Manifest['coreDB']['data'];
          }
        }
      }
    }

    // Auth Configuration Information
    if(!defined("AUTH_ROLES")){ define("AUTH_ROLES", true); }
    if(!defined("AUTH_GROUPS")){ define("AUTH_GROUPS", false); }
    if(!defined("AUTH_TYPE")){ define("AUTH_TYPE", "SESSION"); }

    // coreDB Configuration Information
    if(!defined("ROOT_URL") && $this->URL != 'http:///'){ define("ROOT_URL", $this->URL); }
    if(!defined("COREDB_URL") && defined("ROOT_URL")){ define("COREDB_URL",ROOT_URL); }
    if(!defined("COREDB_LOGO") && defined("COREDB_URL")){ define("COREDB_LOGO",COREDB_URL . "img/logo.png"); }
    if(!defined("COREDB_TRADEMARK") && defined("COREDB_URL")){ define("COREDB_TRADEMARK",COREDB_URL . "trademark"); }
    if(!defined("COREDB_POLICY") && defined("COREDB_URL")){ define("COREDB_POLICY",COREDB_URL . "policy"); }
    if(!defined("COREDB_SUPPORT") && defined("COREDB_URL")){ define("COREDB_SUPPORT",COREDB_URL . "support"); }
    if(!defined("COREDB_CONTACT") && defined("COREDB_URL")){ define("COREDB_CONTACT",COREDB_URL . "contact"); }
    if(!defined("COREDB_DATA")){ define("COREDB_DATA",$this->DataDir); }

    // SMTP Configuration Information
    if(!defined("SMTP_BRAND") && defined("COREDB_BRAND")){ define("SMTP_BRAND",COREDB_BRAND); }
    if(!defined("SMTP_LOGO") && defined("COREDB_LOGO")){ define("SMTP_LOGO",COREDB_LOGO); }
    if(!defined("SMTP_TRADEMARK") && defined("COREDB_TRADEMARK")){ define("SMTP_TRADEMARK",COREDB_TRADEMARK); }
    if(!defined("SMTP_POLICY") && defined("COREDB_POLICY")){ define("SMTP_POLICY",COREDB_POLICY); }
    if(!defined("SMTP_SUPPORT") && defined("COREDB_SUPPORT")){ define("SMTP_SUPPORT",COREDB_SUPPORT); }
    if(!defined("SMTP_CONTACT") && defined("COREDB_CONTACT")){ define("SMTP_CONTACT",COREDB_CONTACT); }

    // coreDB Debug
    if(!defined("COREDB_DEBUG")){ define("COREDB_DEBUG", $this->Debug); }

    // coreDB Maintenance
    if(!defined("COREDB_MAINTENANCE")){ define("COREDB_MAINTENANCE", $this->Maintenance); }
  }

  public function mkdir($directory){
    $make = $this->Path;
    $directories = explode('/',$directory);
    foreach($directories as $subdirectory){
      $make .= '/'.$subdirectory;
      if(!is_file($make)&&!is_dir($make)){ mkdir($make, 0777, true); }
    }
    return $make;
  }

  public function configure($array = []){
    try {
      $this->mkdir('config');
      $config = $this->configurations();
      foreach($array as $key => $value){
        $config[$key] = $value;
      }

      // Encrypt
      foreach($config as $key => $value){
        if(isset($value['password'])){
          if(isset($value['username'])){ $publicKey = $value['username']; }
          if(isset($value['key'])){ $publicKey = $value['key']; }
          $this->Encryption->setPublicKey($publicKey);
          $value['password'] = $this->Encryption->encrypt($value['password']);
          $config[$key] = $value;
        }
      }
      $json = fopen($this->Path . '/config/config.json', 'w');
      fwrite($json, json_encode($config, JSON_PRETTY_PRINT));
      fclose($json);
      return true;
    } catch(Exception $error){
      return false;
    }
  }

  public function configurations($key = null){
    $config = [];
    if(is_file($this->Path . '/config/config.json')){
      $config = json_decode(file_get_contents($this->Path . '/config/config.json'),true);
    }

    // Configure Encryption
    if(!defined("ENCRYPTION_CIPHER") && isset($config['encryption']['cipher'])){ define("ENCRYPTION_CIPHER",$config['encryption']['cipher']); }
    if(!defined("ENCRYPTION_KEY") && isset($config['encryption']['key'])){ define("ENCRYPTION_KEY",$config['encryption']['key']); }
    $this->Encryption->setCipher();
    $this->Encryption->setPrivateKey();

    // Decrypt Configurations
    foreach($config as $configKey => $value){
      if(isset($value['password'])){
        if(isset($value['username'])){ $publicKey = $value['username']; }
        if(isset($value['key'])){ $publicKey = $value['key']; }
        $this->Encryption->setPublicKey($publicKey);
        $value['password'] = $this->Encryption->decrypt($value['password']);
        $config[$configKey] = $value;
      }
    }
    if($key != null){
      if(isset($config[$key])){ return $config[$key]; }
      return null;
    }
    return $config;
  }
}
