<?php

//Declaring namespace
namespace LaswitchTech\coreDB;

//Import phpRouter class into the global namespace
use LaswitchTech\phpRouter\phpRouter;
use LaswitchTech\phpAUTH\Auth;
use LaswitchTech\coreDB\coreDB;

class Router extends phpRouter {

  protected $Auth = null;
  protected $coreDB = null;

  public function __construct(){
    $this->Auth = new Auth("SESSION");
    parent::__construct();
  }

  public function isRoute($route){ return ($route == $this->Route); }

  public function render(){
    $this->coreDB = new coreDB($this->Route,$this->Routes);
    return parent::render();
  }
}
