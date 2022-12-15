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
    $this->Path = dirname(\Composer\Factory::getComposerFile());

    // Configure API
    $this->Configurator = new Configurator();

    // Inititate Parent Constructor
    parent::__construct();
  }
}
