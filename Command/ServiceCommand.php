<?php

//Import BaseCommand class into the global namespace
use LaswitchTech\phpCLI\BaseCommand;

//Import Configurator class into the global namespace
use LaswitchTech\coreDB\Configurator;

//Import CLI class into the global namespace
use LaswitchTech\coreDB\CLI;

class ServiceCommand extends BaseCommand {

  protected $Configurator = null;
  protected $Model = null;

  public function __construct(){

    // Setup Configurator
    $this->Configurator = new Configurator();

    // Initiate Parent Constructor
    parent::__construct();

    // Setup ServiceModel
    $this->Model = new ServiceModel();
  }

  public function enableAction($argv){
    foreach($argv as $service){
      if($this->Model->enableService($service)){
        $this->success('Service ' . $service . ' was enabled');
      }
    }
  }

  public function disableAction($argv){
    foreach($argv as $service){
      if($this->Model->disableService($service)){
        $this->success('Service ' . $service . ' was disabled');
      }
    }
  }

  public function addAction($argv){
    if(count($argv) > 0){
      $type = $argv[0];
      unset($argv[0]);
      if(count($argv) > 0){
        $name = $argv[1];
        unset($argv[1]);
        if(count($argv) > 0){
          switch($type){
            case"command":
              if($this->Model->addCommand($name,array_values($argv))){
                $this->success('Command ' . $name . ' was added');
              } else {
                $this->error('Unable to create command: ' . $name);
              }
              break;
            case"service":
              if($this->Model->addService($name,array_values($argv))){
                $this->success('Service ' . $name . ' was added');
              } else {
                $this->error('Unable to create service: ' . $name);
              }
              break;
          }
        } else {
          switch($type){
            case"command":
              $this->error('You must provide action(s) for the command to be executable');
              break;
            case"service":
              $this->error('You must provide command(s) for the service to execute');
              break;
          }
        }
      } else {
        $this->error('You must provide a name for the ' . ucfirst($type));
      }
    } else {
      $this->error('You must provide a type [service/command]');
    }
  }

  public function listAction($argv){
    // Retrieve Services
    $services = $this->Model->getServices(0);
    foreach($services as $name => $service){
      if($service['status'] > 0){
        $this->success(' - ' . $name);
      } else {
        $this->error(' - ' . $name);
      }
    }
  }

  public function scheduleAction($argv){
    if(count($argv) > 0){
      $name = $argv[0];
      if(count($argv) > 1){
        $frequency = strtoupper($argv[1]);
        $frequencies = [
          "ALWAYS",
          "HOURLY",
          "DAILY",
          "WEEKLY",
          "MONTHLY",
          "YEARLY",
        ];
        if(in_array($frequency,$frequencies)){
          //
        } else {
          $this->error('This frequency is not supported');
        }
      } else {
        $this->error('You must specify the frequency for service [ALWAYS,HOURLY,DAILY,WEEKLY,MONTHLY,YEARLY]');
      }
    } else {
      $this->error('You must specify the name of the service');
    }
  }

  public function startAction($argv){

    // Save Current DateTime
    $year = date('Y');
    $month = date('m');
    $week = date('W');
    $day = date('d');
    $weekday = date('w');
    $hour = date('H');

    // Retrieve Services
    $services = $this->Model->getServices();

    // Build Service Commands
    foreach($services as $name => $service){

      // Process last_excution
      $timestamp = strtotime($service['last_execution']);

      // Process Service's DateTime
      $yearService = date('Y',$timestamp);
      $monthService = date('m',$timestamp);
      $weekService = date('W',$timestamp);
      $dayService = date('d',$timestamp);
      $weekdayService = date('w',$timestamp);
      $hourService = date('H',$timestamp);

      // Init Switches
      $executionSwitch = false;
      $frequencySwitch = false;
      $scheduleSwitch = false;

      // Eval Schedule Switch
      if($service['schedule'] == null){ $scheduleSwitch = true; }

      // Eval Frequency Switch
      switch($service['frequency']){
        case "ALWAYS": $frequencySwitch = true;break;
        case "HOURLY":
          if($hour > $hourService){ $frequencySwitch = true; }
          break;
        case "DAILY":
          if($day > $dayService){ $frequencySwitch = true; }

          // Eval Schedule Switch
          if($frequencySwitch && !$scheduleSwitch){
            if($service['schedule']['hour'] >= $hour){ $scheduleSwitch = true; }
          }
          break;
        case "WEEKLY":
          if($week > $weekService){ $frequencySwitch = true; }

          // Eval Schedule Switch
          if($frequencySwitch && !$scheduleSwitch){
            if(isset($service['schedule'][$weekday])){
              if(isset($service['schedule'][$weekday]['hour']) && $service['schedule'][$weekday]['hour'] >= $hour){ $scheduleSwitch = true; }
              elseif(!isset($service['schedule'][$weekday]['hour'])){ $scheduleSwitch = true; }
            }
          }
          break;
        case "MONTHLY":
          if($month > $monthService){ $frequencySwitch = true; }

          // Eval Schedule Switch
          if($frequencySwitch && !$scheduleSwitch){
            if(isset($service['schedule'][$day])){
              if(isset($service['schedule'][$day]['hour']) && $service['schedule'][$day]['hour'] >= $hour){ $scheduleSwitch = true; }
              elseif(!isset($service['schedule'][$day]['hour'])){ $scheduleSwitch = true; }
            }
          }
          break;
        case "YEARLY":
          if($year > $yearService){ $frequencySwitch = true; }

          // Eval Schedule Switch
          if($frequencySwitch && !$scheduleSwitch){
            if(isset($service['schedule'][$month])){
              if(isset($service['schedule'][$month][$day])){
                if(isset($service['schedule'][$month][$day]['hour']) && $service['schedule'][$month][$day]['hour'] >= $hour){ $scheduleSwitch = true; }
                elseif(!isset($service['schedule'][$month][$day]['hour'])){ $scheduleSwitch = true; }
              }
              elseif(empty($service['schedule'][$month])){ $scheduleSwitch = true; }
            }
          }
          break;
      }

      // Eval Execution Switch
      if($frequencySwitch && $scheduleSwitch){ $executionSwitch = true; }

      // Process each services and commands
      if($executionSwitch){
        foreach($service['commands'] as $command){

          // Assemble Command Arguments
          $execute = [$_SERVER['PHP_SELF']];
          foreach(explode(' ',$command) as $argument){
            array_push($execute,$argument);
          }

          // Execute Command
          $result = new CLI($execute);
        }

        // Update Service last_execution
        $this->Model->executed($name);
      }
    }
  }
}
