<?php

// Import Librairies
require_once dirname(__FILE__,3) . '/src/lib/requirements.php';
require_once dirname(__FILE__,3) . '/src/lib/auth.php';
require_once dirname(__FILE__,3) . '/src/lib/installer.php';
require_once dirname(__FILE__,3) . '/src/lib/crud.php';
require_once dirname(__FILE__,3) . '/src/lib/notification.php';
require_once dirname(__FILE__,3) . '/src/lib/option.php';
require_once dirname(__FILE__,3) . '/src/lib/helper.php';
require_once dirname(__FILE__,3) . '/vendor/autoload.php';

class API{

  protected $Settings = [];
  protected $Manifest = [];
  protected $Language = 'english';
  protected $Languages = [];
  protected $Fields = [];
  protected $Timezones;
  protected $Timezone = 'America/Toronto';
  protected $Countries;
  protected $Notification = false;
  protected $Option = false;
  protected $Helper = false;
  protected $States;
  protected $Tables;
  protected $Brand = null;
  protected $PHPVersion;
  protected $Protocol;
  protected $Domain;
  protected $URL;
  protected $Translator = false;
  public $Auth;
  protected $Debug = false;
  protected $Log = "tmp/api.log";
  protected $Logger = false;

  public function __construct(){

    // Init tmp directory
    $this->mkdir('tmp');

    // Configure PHP
    ini_set('memory_limit', '2G');
    ini_set('max_execution_time', 0);
    ini_set('ignore_repeated_errors', 1);
    ini_set("display_errors", 0);
    ini_set("log_errors", 1);
    ini_set("error_log", dirname(__FILE__,3) . "/tmp/error.log");

		// Gathering Server Information
		$this->PHPVersion=substr(phpversion(),0,3);
		if(isset($_SERVER['HTTP_HOST'])){
			$this->Protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")."://";
			$this->Domain = $_SERVER['HTTP_HOST'];
		}

    // Import Configurations
		if(is_file(dirname(__FILE__,3) . "/config/config.json")){
			$this->Settings = json_decode(file_get_contents(dirname(__FILE__,3) . '/config/config.json'),true);
      if($this->Brand == null && isset($this->Settings['name'])){ $this->Brand = $this->Settings['name']; }
		}

    // Import Manifest
		if(is_file(dirname(__FILE__,3) . "/dist/data/manifest.json")){
			$this->Manifest = json_decode(file_get_contents(dirname(__FILE__,3) . '/dist/data/manifest.json'),true);
      if($this->isInstalled() && !isset($this->Settings['name'])){ $this->Settings['name'] = $this->Manifest['name']; $this->setSettings(); }
      if($this->Brand == null){ $this->Brand = $this->Manifest['name']; }
		}

		// Setup Debug
		if(isset($this->Settings['debug']) && $this->Settings['debug']){ $this->Debug = true; }
    if($this->Debug){ error_reporting(E_ALL & ~E_NOTICE); } else { error_reporting(0); }

    // Setup Logger
    $this->Log = dirname(__FILE__,3) . "/tmp/api.log";
		if(isset($this->Settings['log']['api']['status'])){ $this->Logger = $this->Settings['log']['api']['status']; }
    if(isset($this->Settings['log']['api']['location'])){ $this->Log = $this->Settings['log']['api']['location']; }

    // Setup URL
		if(isset($_SERVER['HTTP_HOST'])){
			$this->URL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")."://";
			$this->URL .= $_SERVER['HTTP_HOST'].'/';
      if($this->isInstalled() && !isset($this->Settings['url'])){ $this->Settings['url'] = $this->URL; $this->setSettings(); }
		}

    // Import License
    $this->License = file_get_contents(dirname(__FILE__,3)."/LICENSE");

		//Import Listings
    $this->Timezones = json_decode(file_get_contents(dirname(__FILE__,3) . '/dist/data/timezones.json'),true);
    $this->Countries = json_decode(file_get_contents(dirname(__FILE__,3) . '/dist/data/countries.json'),true);
    $this->States = json_decode(file_get_contents(dirname(__FILE__,3) . '/dist/data/states.json'),true);

		// Setup Language
		if(isset($_COOKIE['language'])){ $this->Language = $_COOKIE['language']; }
    elseif(isset($this->Settings['language'])){ $this->Language = $this->Settings['language']; }
    $this->setLanguage();

		// Setup Instance Timezone
		if(isset($this->Settings['timezone'])){ $this->Timezone = $this->Settings['timezone']; }
    date_default_timezone_set($this->Timezone);

    // Setup Translator
    if(isset($this->Settings['gkey'])){
      $this->Translator = new Google\Cloud\Translate\V2\TranslateClient(['key' => $this->Settings['gkey']]);
    }

    // Setup Auth
    $this->Auth = new Auth($this->Settings,$this->Manifest,$this->Fields);
    $this->setUserLanguage();

    // Setup Notifications
    $this->Notification = new Notification($this->Auth);

    // Setup Options
    $this->Option = new Option($this->Auth);

    // Setup Helpers
    $this->Helper = new Helper($this->Auth, $this->Notification);
    $configure = function($init){
      foreach($init as $property => $methods){
        foreach($methods as $method => $data){
          if($property == 'this' && method_exists($this, $method)){
            $this->$method($data);
          } else {
            if(property_exists($this, $property) && method_exists($this->$property, $method)){
              $this->$property->$method($data);
            }
          }
        }
      }
    };
    foreach($this->Helper->init() as $plugin => $init){
      if($this->isAssoc($init)){ $configure($init); } else {
        foreach($init as $run){ $configure($run); }
      }

    }

    // Prevent Lockouts
    if(session_status() == PHP_SESSION_ACTIVE && !empty($_SESSION) && !$this->isInstalled()){
      $this->Auth->isLogout();
    }
  }

  private function setLanguage($language = null){
    if($language == null){ $language = $this->Language; }
    $languages = array_diff(scandir(dirname(__FILE__,3) . "/dist/languages/"), array('.', '..'));
    foreach($languages as $key => $value){ array_push($this->Languages,str_replace('.json','',$value)); }
    if(in_array($language,$this->Languages)){
      $this->Language = $language;
      $this->Fields = json_decode(file_get_contents(dirname(__FILE__,3) . "/dist/languages/".$language.".json"),true);
    }
  }

  protected function isAssoc(array $arr){
    if (array() === $arr) return false;
    return array_keys($arr) !== range(0, count($arr) - 1);
  }

  private function setUserLanguage(){
    if($this->Auth->isLogin() && isset($this->Auth->User['language'])){
      if($this->Auth->User['language'] != null && $this->Auth->User['language'] != $this->Language){ $this->setLanguage($this->Auth->User['language']); }
    }
  }

  protected function mkdir($directory){
    $make = dirname(__FILE__,3);
    $directories = explode('/',$directory);
    foreach($directories as $subdirectory){
      $make .= '/'.$subdirectory;
      if(!is_file($make)&&!is_dir($make)){ mkdir($make); }
    }
    return $make;
  }

  protected function error($log = [], $force = false){
    $this->log(json_encode($log, JSON_PRETTY_PRINT),$force);
    exit();
  }

  protected function log($txt = " ", $force = false){
    if(is_bool($txt)){ $txt = $txt ? 'true' : 'false'; }
    if(!is_string($txt)){ $txt = json_encode($txt, JSON_PRETTY_PRINT); }
    $txt = "[".date("Y-m-d H:i:s")."][".$this->Auth->getClientIP()."]".$txt;
    if(defined('STDIN')){ echo $txt."\n"; }
    if($force || $this->Logger){
      return file_put_contents($this->Log, $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
    }
  }

  public function init(){
    $return = [
      "success" => $this->getField('Initialized'),
      "output" => [
        "timezones" => $this->Timezones,
        "timezone" => $this->Timezone,
        "language" => $this->Language,
        "languages" => $this->Languages,
        "countries" => $this->Countries,
        "license" => $this->License,
        "states" => $this->States,
        "fields" => $this->Fields,
        "brand" => $this->Brand,
        "debug" => $this->Debug,
        "installed" => $this->isInstalled(),
      ],
    ];
    if($this->isInstalled()){
      $return['output']['administration'] = $this->Settings['administration'];
      if($this->Auth->SQL->database->isConnected()){
        $return['output']['tables'] = $this->Auth->SQL->database->getTables();
      }
      if($this->Auth->isLogin()){
        $return['output']['user'] = $this->Auth->User;
        $return['output']['groups'] = $this->Auth->Groups;
        $return['output']['roles'] = $this->Auth->Roles;
        $return['output']['permissions'] = $this->Auth->Permissions;
        $return['output']['options'] = $this->Auth->Options;
      }
      if($this->Notification){
        $return['output']['notifications'] = $this->Notification->read();
        if($this->Auth->isLogin()){
          $return['output']['options']['notifications'] = $this->Notification->getSettings($this->Auth->User['id']);
        }
      }
    }
    return $return;
  }

  public function readNotification($data = []){
    return [
      "success" => $this->getField('Notification viewed'),
      "output" => [
        "notifications" => $this->Notification->read($data),
      ],
    ];
  }

  protected function setSettings($settings = []){
    if(!is_array($settings) || empty($settings)){ $settings = $this->Settings; }
    if(isset($settings['debug'])){ $this->Debug = $settings['debug']; }
    try {
      $this->mkdir('config');
      $json = fopen(dirname(__FILE__,3).'/config/config.json', 'w');
  		fwrite($json, json_encode($settings, JSON_PRETTY_PRINT));
  		fclose($json);
      return true;
    } catch(Exception $error){ return false; }
  }

  protected function setManifest($manifest = []){
    if(!is_array($manifest) || empty($manifest)){ $manifest = $this->Manifest; }
    try {
      $this->mkdir('dist/data');
      $json = fopen(dirname(__FILE__,3).'/dist/data/manifest.json', 'w');
  		fwrite($json, json_encode($manifest, JSON_PRETTY_PRINT));
  		fclose($json);
      return true;
    } catch(Exception $error){ return false; }
  }

  protected function setVersion($version){
    $version = [
      "label" => "Version",
      "message" => $version,
      "color" => "success",
      "schemaVersion" => 1,
    ];
    try {
      $this->mkdir('dist/data');
      $json = fopen(dirname(__FILE__,3).'/dist/data/version.json', 'w');
  		fwrite($json, json_encode($version, JSON_PRETTY_PRINT));
  		fclose($json);
      return true;
    } catch(Exception $error){ return false; }
  }

  protected function setBuild($build){
    $build = [
      "label" => "Build",
      "message" => "$build",
      "color" => "success",
      "schemaVersion" => 1,
    ];
    try {
      $this->mkdir('dist/data');
      $json = fopen(dirname(__FILE__,3).'/dist/data/build.json', 'w');
  		fwrite($json, json_encode($build, JSON_PRETTY_PRINT));
  		fclose($json);
      return true;
    } catch(Exception $error){ return false; }
  }

  public function getField($field){
    if(isset($this->Fields[$field])){ return $this->Fields[$field]; }
    else { return 'Unknown field ['.$field.'] in '.$this->Language; }
  }

  public function isDebugger(){
    return $this->Debug;
  }

  public function isInstalled(){
    return is_file(dirname(__FILE__,3).'/config/config.json') && !empty($this->Settings);
  }

  public function saveOption($data = []){
    if($return['output'] = $this->Option->save($data)){
      $return['success'] = $this->getField("Option(s) saved");
    } else { return['error' => $this->getField("Unable to save option(s)")]; }
    return $return;
  }

  public function logout(){
    if($this->Auth->logout()){
      return [
  			"success" => $this->getField('Signed out'),
  			"output" => [
  				"status" => $this->Auth->isLogin(),
  			],
  		];
    }
  }

  public function getUserRelations($id = []){
    if($this->Auth->isLogin() && $this->Auth->isActivated() && !$this->Auth->isDeactivated()){
      if(empty($id)){ $id = $this->Auth->User['id']; }
      if($id == $this->Auth->User['id'] || $this->Auth->isAllowed('isAdministrator')){
        return [
          "success" => $this->getField('Relations fetched'),
          "output" => [
            "to" => $this->Auth->SQL->database->getRelationshipsTo('users',$id),
            "of" => $this->Auth->SQL->database->getRelationshipsOf('users',$id),
          ],
        ];
      } else {
        return ["error" => $this->getField('You are not authorized')];
      }
    }
  }

  public function saveSettings($settings){
    if($this->Auth->isLogin() && $this->Auth->isActivated() && !$this->Auth->isDeactivated() && $this->Auth->isAllowed('isAdministrator')){
      $configurations = [];
      $errors = [];
      foreach($settings as $config => $configs){
        foreach($configs as $setting => $value){
          if(isset($this->Settings[$config][$setting])){
            $configurations[$config][$setting] = $value;
          } elseif(isset($this->Settings[$setting])){ $configurations[$setting] = $value; }
        }
      }
      if(isset($configurations['sql'])){
        if(isset($configurations['sql']['host'],$configurations['sql']['username'],$configurations['sql']['password'],$configurations['sql']['database'])){
          $db = new Database($configurations['sql']['host'],$configurations['sql']['username'],$configurations['sql']['password'],$configurations['sql']['database']);
          if(!$db->isConnected()){
            unset($configurations['sql']);
            $errors['sql'] = $this->getField('Unable to authenticate on SQL server');
          }
        } else { unset($configurations['sql']);$errors['sql'] = $this->getField('Unable to authenticate on SQL server'); }
      }
      if(isset($configurations['smtp'])){
        if(isset($configurations['smtp']['host'],$configurations['smtp']['encryption'],$configurations['smtp']['port'],$configurations['smtp']['username'],$configurations['smtp']['password'])){
          if(!$this->Auth->SMTP->login($configurations['smtp']['username'],$configurations['smtp']['password'],$configurations['smtp']['host'],$configurations['smtp']['port'],$configurations['smtp']['encryption'])){
            unset($configurations['smtp']);
            $errors['smtp'] = $this->getField('Unable to authenticate on SMTP server');
          }
        } else { unset($configurations['smtp']);$errors['smtp'] = $this->getField('Unable to authenticate on SMTP server'); }
      }
      if(isset($configurations['imap'])){
        if(isset($configurations['imap']['host'],$configurations['imap']['encryption'],$configurations['imap']['port'],$configurations['imap']['username'],$configurations['imap']['password'])){
          if(!$this->Auth->IMAP->login($configurations['imap']['username'], $configurations['imap']['password'],$configurations['imap']['host'],$configurations['imap']['port'],$configurations['imap']['encryption'])){
            unset($configurations['imap']);
            $errors['imap'] = $this->getField('Unable to authenticate on IMAP server');
          }
        } else { unset($configurations['imap']);$errors['imap'] = $this->getField('Unable to authenticate on IMAP server'); }
      }
      if(empty($errors)){
        if($this->setSettings($configurations)){
          return [ 'success' => $this->getField('Settings successfully saved!') ];
        } else {
          $errors['settings'] = $this->getField('Unable to save settings');
        }
      }
    }
    return [
      "error" => $this->getField('Unable to save settings'),
      "output" => $errors,
    ];
  }

  public function getSettings(){
    if($this->Auth->isLogin() && $this->Auth->isActivated() && !$this->Auth->isDeactivated() && $this->Auth->isAllowed('isAdministrator')){
      $settings = [];
      foreach($this->Settings as $category => $config){
        $configs = [];
        if(!is_array($config)){
          $configs[$category] = $config;
          $category = 'general';
        } else { $configs = $config; }
        foreach($configs as $setting => $value){
          $default = [
            "value" => $value,
            "component" => "input",
            "type" => "text",
            "icon" => "fa-solid fa-cog",
            "translate" => false,
            "show" => true,
            "list" => [],
          ];
          switch($setting){
            case"language":
              $default['icon'] = "fa-solid fa-atlas";
              $default['list'] = $this->Languages;
              $default['component'] = "select";
              break;
            case"timezone":
              $default['icon'] = "fa-solid fa-globe-americas";
              $default['list'] = $this->Timezones;
              $default['component'] = "select";
              break;
            case"encryption":
              $default['icon'] = "fa-solid fa-lock";
              $default['list'] = [
                "none" => "None",
                "ssl" => "SSL",
                "starttls" => "STARTTLS",
              ];
              $default['component'] = "select";
              break;
            case"username":
              if($category == "sql"){
                $default['icon'] = "fa-solid fa-user";
                $default['type'] = "text";
              } else {
                $default['icon'] = "fa-solid fa-at";
                $default['type'] = "email";
              }
              break;
            case"host":
              $default['icon'] = "fa-solid fa-server";
              break;
            case"port":
              $default['icon'] = "fa-solid fa-plug";
              break;
            case"database":
              $default['icon'] = "fa-solid fa-database";
              break;
            case"url":
              $default['icon'] = "fa-solid fa-globe";
              break;
            case"name":
              $default['icon'] = "fa-solid fa-fingerprint";
              $default['show'] = $this->Debug;
              break;
            case"administration":
              $default['icon'] = "fa-solid fa-envelope";
              break;
            case"password":
              $default['icon'] = "fa-solid fa-user-lock";
              $default['type'] = "password";
              break;
            case"gkey":
              $default['icon'] = "fa-brands fa-google";
              break;
            case"debug":
              $default['show'] = false;
              break;
            default:
              break;
          }
          $settings[$category][$setting] = $default;
        }
      }
      ksort($settings);
      return [
        "success" => $this->getField('Settings fetched'),
        "output" => $settings,
      ];
    } else {
      return ["error" => $this->getField('You are not authorized')];
    }
  }
}
