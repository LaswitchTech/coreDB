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
    $this->Path = dirname(\Composer\Factory::getComposerFile());

    // Configure API
    $this->Configurator = new Configurator();

    // Inititate Parent Constructor
    parent::__construct($argv);
  }
}
