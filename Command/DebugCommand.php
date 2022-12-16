<?php

//Import BaseCommand class into the global namespace
use LaswitchTech\phpCLI\BaseCommand;

class DebugCommand extends BaseCommand {

  public function toggleAction($argv){
    if(is_file($this->Path . '/config/config.json')){
      $config = json_decode(file_get_contents($this->Path . '/config/config.json'),true);
    }
    if(!isset($config['debug'])){ $config['debug'] = false; }
    if($this->save(["debug" => !$config['debug']])){
      if(!$config['debug']){
        $this->success("Debug turned on");
      } else {
        $this->success("Debug turned off");
      }
    } else {
      $this->error("Unable to save configurations");
    }
  }

  public function onAction($argv){
    if($this->save(["debug" => true])){
      $this->success("Debug turned on");
    } else {
      $this->error("Unable to save configurations");
    }
  }

  public function offAction($argv){
    if($this->save(["debug" => false])){
      $this->success("Debug turned off");
    } else {
      $this->error("Unable to save configurations");
    }
  }

  public function serverAction($argv){
    $this->info('$_SERVER:');
    $this->warning(json_encode($_SERVER,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
  }

  public function configurationsAction($argv){
    $this->info('Configurations:');
    $config = [];
    if(is_file($this->Path . '/config/config.json')){
      $config = json_decode(file_get_contents($this->Path . '/config/config.json'),true);
    }
    $this->warning(json_encode($config,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
  }

  public function allAction($argv){
    $this->configurationsAction($argv);
    $this->output('');
    $this->serverAction($argv);
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
