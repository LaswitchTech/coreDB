<?php

//Import BaseCommand class into the global namespace
use LaswitchTech\phpCLI\BaseCommand;

//Import Configurator class into the global namespace
use LaswitchTech\coreDB\Configurator;

class MaintenanceCommand extends BaseCommand {

  protected $Configurator = null;

  public function __construct(){

    // Setup Configurator
    $this->Configurator = new Configurator();

    // Initiate Parent Constructor
    parent::__construct();
  }

  public function toggleAction($argv){
    $config = [];
    if(is_file($this->Path . '/config/config.json')){
      $config = json_decode(file_get_contents($this->Path . '/config/config.json'),true);
    }
    if(!isset($config['maintenance'])){ $config['maintenance'] = false; }
    if($this->Configurator->configure(["maintenance" => !$config['maintenance']])){
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
    if($this->Configurator->configure(["maintenance" => true])){
      $this->success("Maintenance turned on");
    } else {
      $this->error("Unable to save configurations");
    }
  }

  public function offAction($argv){
    if($this->Configurator->configure(["maintenance" => false])){
      $this->success("Maintenance turned off");
    } else {
      $this->error("Unable to save configurations");
    }
  }
}
