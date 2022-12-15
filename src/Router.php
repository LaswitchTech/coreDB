<?php

//Declaring namespace
namespace LaswitchTech\coreDB;

//Import phpRouter class into the global namespace
use LaswitchTech\phpRouter\phpRouter;

//Import Auth class into the global namespace
use LaswitchTech\phpAUTH\Auth;

//Import coreDB and Configurator class into the global namespace
use LaswitchTech\coreDB\coreDB;
use LaswitchTech\coreDB\Configurator;

//Import Factory class into the global namespace
use Composer\Factory;

class Router extends phpRouter {

  protected $Auth = null;
  protected $coreDB = null;
  protected $Configurator = null;
  protected $Path = null;
  protected $Debug = false;

  public function __construct(){

    // Save Root Path
    $this->Path = dirname(\Composer\Factory::getComposerFile());

    // Configure Router
    $this->Configurator = new Configurator();

    // Initiate Auth
    $this->Auth = new Auth("SESSION");

    // Inititate Parent Constructor
    parent::__construct();
  }

  protected function configure(){
  }

  public function isRoute($route){ return ($route == $this->Route); }

  public function render(){
    $this->coreDB = new coreDB($this->Route,$this->Routes);
    return parent::render();
  }
}
