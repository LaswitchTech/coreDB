<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

class ServiceModel extends BaseModel {

  public function addCommand($command,$actions){
    return $this->insert("INSERT INTO commands (command,actions) VALUES (?,?)", [$command,json_encode($actions,JSON_UNESCAPED_SLASHES)]);
  }

  public function addService($name,$list,$frequency = "ALWAYS", $schedule = null){
    if(is_array($list)){
      $frequency = strtoupper($frequency);
      $frequencies = [
        "ALWAYS",
        "HOURLY",
        "DAILY",
        "WEEKLY",
        "MONTHLY",
        "YEARLY",
      ];
      if(!in_array($frequency,$frequencies)){ $frequency = "ALWAYS"; }
      if($schedule != null){ $schedule = json_encode($schedule,JSON_UNESCAPED_SLASHES); }
      $commands = $this->getCommands();
      foreach($list as $key => $command){
        $cmd = explode(' ',$command)[0];
        $action = explode(' ',$command)[1];
        if(!isset($commands[$cmd]) || !in_array($action,$commands[$cmd]['actions'])){
          unset($list[$key]);
        }
      }
      return $this->insert("INSERT INTO services (name,commands,frequency,schedule) VALUES (?,?,?)", [$name,json_encode($list,JSON_UNESCAPED_SLASHES),$frequency,$schedule]);
    }
  }

  public function getCommands(){
    $results = [];
    $commands = $this->select("SELECT * FROM commands", []);
    foreach($commands as $command){
      $command['actions'] = json_decode($command['actions'],true);
      $results[$command['command']] = $command;
    }
    return $results;
  }

  public function getServices(){
    $results = [];
    $services = $this->select("SELECT * FROM services WHERE status > ?", [0]);
    foreach($services as $service){
      $service['commands'] = json_decode($service['commands'],true);
      if($service['schedule'] != null){ $service['schedule'] = json_decode($service['schedule'],true); }
      $results[$service['name']] = $service;
    }
    return $results;
  }

  public function enableService($name){
    return $this->update("UPDATE services SET status = ? WHERE name = ?", [1,$name]);
  }

  public function disableService($name){
    return $this->update("UPDATE services SET status = ? WHERE name = ?", [0,$name]);
  }

  public function executed($name){
    return $this->update("UPDATE services SET last_execution = ? WHERE name = ?", [date("Y-m-d H:i:s"),$name]);
  }
}
