<?php

//Import BaseCommand class into the global namespace
use LaswitchTech\phpCLI\BaseCommand;

class MaintenanceCommand extends BaseCommand {

  public function toggleAction($argv){
    $config = [];
    if(is_file($this->Path . '/config/config.json')){
      $config = json_decode(file_get_contents($this->Path . '/config/config.json'),true);
    }
    if(!isset($config['maintenance'])){ $config['maintenance'] = false; }
    if($this->save(["maintenance" => !$config['maintenance']])){
      if(!$config['']){
        $this->success("Maintenance turned on");
      } else {
        $this->success("Maintenance turned off");
      }
    } else {
      $this->error("Unable to save configurations");
    }
  }

  public function onAction($argv){
    if($this->save(["maintenance" => true])){
      $this->success("Maintenance turned on");
    } else {
      $this->error("Unable to save configurations");
    }
  }

  public function offAction($argv){
    if($this->save(["maintenance" => false])){
      $this->success("Maintenance turned off");
    } else {
      $this->error("Unable to save configurations");
    }
  }

  protected function save($array = []){
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
}
