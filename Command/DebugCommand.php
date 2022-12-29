<?php

//Import BaseCommand class into the global namespace
use LaswitchTech\phpCLI\BaseCommand;

//Import Configurator class into the global namespace
use LaswitchTech\coreDB\Configurator;

class DebugCommand extends BaseCommand {

  protected $Configurator = null;

  public function __construct(){

    // Setup Configurator
    $this->Configurator = new Configurator();

    // Initiate Parent Constructor
    parent::__construct();
  }

  public function toggleAction($argv){
    if(is_file($this->Path . '/config/config.json')){
      $config = json_decode(file_get_contents($this->Path . '/config/config.json'),true);
    }
    if(!isset($config['debug'])){ $config['debug'] = false; }
    if($this->Configurator->configure(["debug" => !$config['debug']])){
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
    if($this->Configurator->configure(["debug" => true])){
      $this->success("Debug turned on");
    } else {
      $this->error("Unable to save configurations");
    }
  }

  public function offAction($argv){
    if($this->Configurator->configure(["debug" => false])){
      $this->success("Debug turned off");
    } else {
      $this->error("Unable to save configurations");
    }
  }

  public function serverAction($argv){
    $this->info('$_SERVER:');
    $this->warning(json_encode($_SERVER,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
  }

  public function constantsAction($argv){
    $constants = get_defined_constants();
    foreach($constants as $constant => $value){
      $key = explode('_', $constant);
      if(in_array($key[0],['COREDB','SMTP','ROUTER','ROOT','AUTH','DB'])){
        if(is_array($value)){ $value = json_encode($value,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); }
        $this->output($this->set('[' . $constant . ']', 'cyan') . $this->set(' = ', 'yellow') . $value);
      }
    }
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

  public function testAction($argv){
    // $this->output(json_encode($_SERVER,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
  }
}
