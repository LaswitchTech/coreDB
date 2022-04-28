<?php

class Helper {

  protected $Notification;
  protected $Auth = false;
  protected $Debug = false;
  private $Helpers;

  public function __construct($auth, $notification){
    $this->Auth = $auth;
    $this->Notification = $notification;
    $this->Debug = $this->Auth->Debug;
    if(get_class($this) == 'Helper' && method_exists($this, 'loadHelpers')){
      $this->loadHelpers();
    }
  }

  private function loadHelpers(){
    if(get_class($this) == 'Helper'){
      $this->Helpers = new stdClass();
      foreach($this->plugins() as $plugin){
        if(is_file(dirname(__FILE__,3).'/plugins/'.$plugin.'/helper.php')){
          require_once dirname(__FILE__,3).'/plugins/'.$plugin.'/helper.php';
          $class = $plugin.'Helper';
          $this->Helpers->$plugin = new $class($auth, $notification);
        }
      }
    }
  }

  public function init(){
    $init = [];
    foreach($this->plugins() as $plugin){
      if($this->exist($plugin, 'init')){
        $init[$plugin] = $this->Helpers->$plugin->init();
      }
    }
    return $init;
  }

  public function scan($dir){
    $root = dirname(__FILE__,3);
    $scan = scandir($root.'/'.$dir);
    $files = [];
    foreach($scan as $file){
      if(!in_array(trim($file),['.', '..'])){ $files[] = $file; }
    }
    return $files;
  }

  public function plugins($filename = null){
    $root = dirname(__FILE__,3);
    $plugins = $this->scan('plugins');
    if($filename != null){
      foreach($plugins as $key => $plugin){
        $file = $root.'/plugins/'.$plugin.'/'.$filename;
        if(!is_file($file)){ unset($plugins[$key]); }
      }
    }
    return $plugins;
  }

  protected function isReady(){
    return ($this->Auth && $this->Auth->isLogin()) || ($this->Auth && defined('STDIN'));
  }

  protected function isAssoc(array $arr){
    if (array() === $arr) return false;
    return array_keys($arr) !== range(0, count($arr) - 1);
  }

  public function exist($helper, $method){
    return property_exists($this->Helpers, $helper) && method_exists($this->Helpers->$helper, $method);
  }

  public function forms($name){
    $forms = ["create" => [],"update" => []];
    $tables = $this->Auth->SQL->database->getTables();
    if($this->exist($name, 'forms')){
      $forms = $this->$name->forms($name);
    } elseif(in_array($name,$tables)){
      $headers = $this->Auth->SQL->database->getHeaders($name);
      $primary = $this->Auth->SQL->database->getPrimary($name);
      foreach($headers as $header){
        $input = [
          "icon" => "fa-solid fa-keyboard",
          "value" => null,
          "component" => "input",
          "type" => "text",
          "label" => $header,
          "translate" => true,
          "list" => [],
          "hidden" => false,
        ];
        if($header != $primary){
          $forms['create'][$header] = $input;
          $forms['update'][$header] = $input;
        } else {
          $forms['update'][$header] = $input;
          $forms['update'][$header]['hidden'] = true;
        }
      }
    }
    return $forms;
  }
}
