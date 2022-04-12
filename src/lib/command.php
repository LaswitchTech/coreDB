<?php

// Import Librairies
require_once dirname(__FILE__,3) . '/src/lib/cli.php';

class Command {

  protected $CMD = [];
  protected $options = [];
  protected $data = [];
  protected $current = null;
  protected $CLI;

  public function __construct($argv){
    $this->CLI = new CLI();
    foreach($argv as $argument){
      if(substr($argument, 0, 2) === '--'){
        if($this->current != null){ array_push($this->CMD,['command' => str_replace('--','',$this->current),'options' => str_replace('-','',$this->options),'data' => $this->data]); }
        $this->current = $argument;
        $this->options = [];
        $this->data = [];
      }
      elseif(substr($argument, 0, 1) === '-'){
        if($this->current != null){ array_push($this->options,$argument); }
      }
      else {
        if($this->current != null){ array_push($this->data,$argument); }
      }
    }
    if($this->current != null){ array_push($this->CMD,['command' => str_replace('--','',$this->current),'options' => str_replace('-','',$this->options),'data' => $this->data]); }
    foreach($this->CMD as $cmd){
      $method = $cmd['command'];
      if(method_exists($this->CLI,$method)){
        $this->CLI->$method($cmd['data'],$cmd['options']);
      }
    }
  }
}
