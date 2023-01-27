<?php

//Import BaseCommand class into the global namespace
use LaswitchTech\phpCLI\BaseCommand;

//Import Database class into the global namespace
use LaswitchTech\phpDB\Database;

//Import Configurator class into the global namespace
use LaswitchTech\coreDB\Configurator;

class DevCommand extends BaseCommand {

  protected $Configurator = null;
  protected $Database = null;

  public function __construct(){

    // Setup Configurator
    $this->Configurator = new Configurator();

    // Initiate Database
    $this->Database = new Database();

    // Initiate Parent Constructor
    parent::__construct();
  }

  public function composerAction($argv){
    if(count($argv) > 0){
      foreach($argv as $dependency){
        $this->composer($dependency);
      }
    }
  }

  protected function composer($dependency = null){
    $composer = $this->Path . '/composer';
    if(is_file($composer)){
      try {
        if($dependency == null){
          shell_exec('composer update');
        } else {
          shell_exec('composer require ' . $dependency);
        }
        return true;
      } catch (Error $e) {
        $this->error($e->getMessage().'Internal error');
      }
    }
    return false;
  }

  public function testAction($argv){
    var_dump($this->Database->select('SELECT DISTINCT type FROM files'));
  }
}
