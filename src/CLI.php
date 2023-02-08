<?php

//Declaring namespace
namespace LaswitchTech\coreDB;

//Import phpAPI class into the global namespace
use LaswitchTech\phpCLI\phpCLI;

class CLI extends phpCLI {

  protected $Configurator = null;
  protected $Path = null;

  public function __construct($argv){

    // Save Root Path
    if(!defined("ROOT_PATH")){ define("ROOT_PATH",dirname(__DIR__)); }
    $this->Path = ROOT_PATH;

    // Configure API
    $this->Configurator = new Configurator();

    // Inititate Parent Constructor
    parent::__construct($argv);
  }
}
