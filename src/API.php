<?php

//Declaring namespace
namespace LaswitchTech\coreDB;

//Import phpAPI class into the global namespace
use LaswitchTech\phpAPI\phpAPI;

class API extends phpAPI {

  protected $Configurator = null;
  protected $Path = null;

  public function __construct(){

    // Save Root Path
    if(!defined("ROOT_PATH")){ define("ROOT_PATH",dirname(__DIR__)); }
    $this->Path = ROOT_PATH;

    // Configure API
    $this->Configurator = new Configurator();

    // Inititate Parent Constructor
    parent::__construct();
  }
}
