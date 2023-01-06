<?php

//Import BaseCommand class into the global namespace
use LaswitchTech\phpCLI\BaseCommand;

//Import Configurator class into the global namespace
use LaswitchTech\coreDB\Configurator;

//Import CLI class into the global namespace
use LaswitchTech\coreDB\CLI;

class SchedulerCommand extends BaseCommand {

  protected $Configurator = null;
  protected $Model = null;

  public function __construct(){

    // Setup Configurator
    $this->Configurator = new Configurator();

    // Initiate Parent Constructor
    parent::__construct();

    // Setup SchedulerModel
    $this->Model = new SchedulerModel();
  }

  public function enableAction($argv){
    foreach($argv as $schedule){
      if($this->Model->enableSchedule($schedule)){
        $this->success('Schedule ' . $schedule . ' was enabled');
      }
    }
  }

  public function disableAction($argv){
    foreach($argv as $schedule){
      if($this->Model->disableSchedule($schedule)){
        $this->success('Schedule ' . $schedule . ' was disabled');
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
            case"schedule":
              if($this->Model->addSchedule($name,array_values($argv))){
                $this->success('Schedule ' . $name . ' was added');
              } else {
                $this->error('Unable to create schedule: ' . $name);
              }
              break;
          }
        } else {
          switch($type){
            case"command":
              $this->error('You must provide action(s) for the command to be executable');
              break;
            case"schedule":
              $this->error('You must provide command(s) for the schedule to execute');
              break;
          }
        }
      } else {
        $this->error('You must provide a name for the ' . ucfirst($type));
      }
    } else {
      $this->error('You must provide a type [schedule/command]');
    }
  }

  public function listAction($argv){
    $schedules = $this->Model->getSchedules(0);
    foreach($schedules as $name => $schedule){
      if($schedule['status'] > 0){
        $this->success(' - ' . $name);
      } else {
        $this->error(' - ' . $name);
      }
    }
  }

  public function scheduleAction($argv){
    if(count($argv) > 0){
      $name = $argv[0];
      $frequencies = [
        "ALWAYS",
        "HOURLY",
        "DAILY",
        "WEEKLY",
        "MONTHLY",
        "YEARLY",
      ];
      $hour = null;
      $day = null;
      $month = null;
      $weekday = null;
      $schedule = [];
      $this->output('Configuring schedule for ' . $name);
      $frequency = $this->input('At what frequency should the schedule run?',$frequencies,'ALWAYS');
      $frequency = strtoupper($frequency);
      switch($frequency){
        case "WEEKLY":
          $weekday = intval($this->input('On which day of the week?(0 for Sunday, 6 for Saturday)','0'));
          $hour = intval($this->input('At what hour should it run?(0-24)','0'));
          break;
        case "YEARLY":
          $month = intval($this->input('On which month?(1-12)','1'));
        case "MONTHLY":
          $day = intval($this->input('On which day of the month?(1-31)','1'));
        case "DAILY":
          $hour = intval($this->input('At what hour should it run?(0-24)','0'));
          break;
      }
      if($month != null){ $schedule['month'] = $month; }
      if($day != null){ $schedule['day'] = $day; }
      if($weekday != null){ $schedule['weekday'] = $weekday; }
      if($hour != null){ $schedule['hour'] = $hour; }
      if($this->Model->setSchedule($name,$frequency,$schedule)){
        $this->success("Schedule " . $name . "'s schedule was updated");
      } else {
        $this->error("Unable to update " . $name . "'s schedule");
      }
    } else {
      $this->error('You must specify the name of the schedule');
    }
  }

  public function startAction($argv){

    // Save Current DateTime
    $year = intval(date('Y'));
    $month = intval(date('m'));
    $week = intval(date('W'));
    $day = intval(date('d'));
    $weekday = intval(date('w'));
    $hour = intval(date('H'));

    // Retrieve Schedules
    $schedules = $this->Model->getSchedules();

    // Build Schedule Commands
    foreach($schedules as $name => $schedule){

      // Process last_excution
      $timestamp = strtotime($schedule['last_execution']);

      // Process Schedule's DateTime
      $yearSchedule = intval(date('Y',$timestamp));
      $monthSchedule = intval(date('m',$timestamp));
      $weekSchedule = intval(date('W',$timestamp));
      $daySchedule = intval(date('d',$timestamp));
      $weekdaySchedule = intval(date('w',$timestamp));
      $hourSchedule = intval(date('H',$timestamp));

      // Init Switches
      $executionSwitch = false;
      $frequencySwitch = false;
      $scheduleSwitch = false;

      // Eval Schedule Switch
      if($schedule['schedule'] == null){ $scheduleSwitch = true; }

      // Eval Frequency Switch
      switch($schedule['frequency']){
        case "ALWAYS": $frequencySwitch = true;break;
        case "HOURLY":
          if($hour > $hourSchedule){ $frequencySwitch = true; }
          break;
        case "DAILY":
          if($day > $daySchedule){ $frequencySwitch = true; }

          // Eval Schedule Switch
          if($frequencySwitch && !$scheduleSwitch){
            if(isset($schedule['schedule']['hour']) && $schedule['schedule']['hour'] <= $hour){
              $scheduleSwitch = true;
            }
          }
          break;
        case "WEEKLY":
          if($week > $weekSchedule){ $frequencySwitch = true; }

          // Eval Schedule Switch
          if($frequencySwitch && !$scheduleSwitch){
            if(isset($schedule['schedule']['weekday'])){
              if($schedule['schedule']['weekday'] <= $weekday){
                if(isset($schedule['schedule']['hour'])){
                  if($schedule['schedule']['hour'] <= $hour){
                    $scheduleSwitch = true;
                  }
                } else {
                  $scheduleSwitch = true;
                }
              }
            } else {
              if(isset($schedule['schedule']['hour']) && $schedule['schedule']['hour'] <= $hour){
                $scheduleSwitch = true;
              }
            }
          }
          break;
        case "MONTHLY":
          if($month > $monthSchedule){ $frequencySwitch = true; }

          // Eval Schedule Switch
          if($frequencySwitch && !$scheduleSwitch){
            if(isset($schedule['schedule']['day'])){
              if($schedule['schedule']['day'] <= $day){
                if(isset($schedule['schedule']['hour'])){
                  if($schedule['schedule']['hour'] <= $hour){
                    $scheduleSwitch = true;
                  }
                } else {
                  $scheduleSwitch = true;
                }
              }
            } else {
              if(isset($schedule['schedule']['hour']) && $schedule['schedule']['hour'] <= $hour){
                $scheduleSwitch = true;
              }
            }
          }
          break;
        case "YEARLY":
          if($year > $yearSchedule){ $frequencySwitch = true; }

          // Eval Schedule Switch
          if($frequencySwitch && !$scheduleSwitch){
            if(isset($schedule['schedule']['month'])){
              if($schedule['schedule']['month'] <= $month){
                if(isset($schedule['schedule']['day'])){
                  if($schedule['schedule']['day'] <= $day){
                    if(isset($schedule['schedule']['hour'])){
                      if($schedule['schedule']['hour'] <= $hour){
                        $scheduleSwitch = true;
                      }
                    } else {
                      $scheduleSwitch = true;
                    }
                  }
                } else {
                  if(isset($schedule['schedule']['hour']) && $schedule['schedule']['hour'] <= $hour){
                    $scheduleSwitch = true;
                  }
                }
              }
            } else {
              if(isset($schedule['schedule']['day'])){
                if($schedule['schedule']['day'] <= $day){
                  if(isset($schedule['schedule']['hour'])){
                    if($schedule['schedule']['hour'] <= $hour){
                      $scheduleSwitch = true;
                    }
                  } else {
                    $scheduleSwitch = true;
                  }
                }
              } else {
                if(isset($schedule['schedule']['hour']) && $schedule['schedule']['hour'] <= $hour){
                  $scheduleSwitch = true;
                }
              }
            }
          }
          break;
      }

      // Eval Execution Switch
      if($frequencySwitch && $scheduleSwitch){ $executionSwitch = true; }

      // Process each schedules and commands
      if($executionSwitch){
        foreach($schedule['commands'] as $command){

          // Assemble Command Arguments
          $execute = [$_SERVER['PHP_SELF']];
          foreach(explode(' ',$command) as $argument){
            array_push($execute,$argument);
          }

          // Execute Command
          $result = new CLI($execute);
        }

        // Update Schedule last_execution
        $this->Model->executed($name);
      }
    }
  }
}
