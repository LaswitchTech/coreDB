<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

class SchedulerModel extends BaseModel {

  public function addCommand($command,$actions){
    return $this->insert("INSERT INTO scheduler_commands (command,actions) VALUES (?,?)", [$command,json_encode($actions,JSON_UNESCAPED_SLASHES)]);
  }

  public function addSchedule($name,$list,$frequency = "ALWAYS", $schedule = []){
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
      $schedule = json_encode($schedule,JSON_UNESCAPED_SLASHES);
      $commands = $this->getCommands();
      foreach($list as $key => $command){
        $cmd = explode(' ',$command)[0];
        $action = explode(' ',$command)[1];
        if(!isset($commands[$cmd]) || !in_array($action,$commands[$cmd]['actions'])){
          unset($list[$key]);
        }
      }
      return $this->insert("INSERT INTO scheduler_schedules (name,commands,frequency,schedule) VALUES (?,?,?,?)", [$name,json_encode($list,JSON_UNESCAPED_SLASHES),$frequency,$schedule]);
    }
  }

  public function setSchedule($name,$frequency = "ALWAYS", $schedule = []){
    $schedule = json_encode($schedule,JSON_UNESCAPED_SLASHES);
    return $this->update("UPDATE scheduler_schedules SET frequency = ?, schedule = ? WHERE name = ?", [$frequency,$schedule,$name]);
  }

  public function getCommands(){
    $results = [];
    $commands = $this->select("SELECT * FROM scheduler_commands", []);
    foreach($commands as $command){
      $command['actions'] = json_decode($command['actions'],true);
      $results[$command['command']] = $command;
    }
    return $results;
  }

  public function getSchedules($status = 1){
    $results = [];
    $schedules = $this->select("SELECT * FROM scheduler_schedules WHERE status >= ?", [$status]);
    foreach($schedules as $schedule){
      $schedule['commands'] = json_decode($schedule['commands'],true);
      if($schedule['schedule'] != null){ $schedule['schedule'] = json_decode($schedule['schedule'],true); }
      $results[$schedule['name']] = $schedule;
    }
    return $results;
  }

  public function enableSchedule($name){
    return $this->update("UPDATE scheduler_schedules SET status = ? WHERE name = ?", [1,$name]);
  }

  public function disableSchedule($name){
    return $this->update("UPDATE scheduler_schedules SET status = ? WHERE name = ?", [0,$name]);
  }

  public function executed($name){
    return $this->update("UPDATE scheduler_schedules SET last_execution = ? WHERE name = ?", [date("Y-m-d H:i:s"),$name]);
  }
}
