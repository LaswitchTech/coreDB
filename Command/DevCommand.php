<?php

//Import BaseCommand class into the global namespace
use LaswitchTech\phpCLI\BaseCommand;

class DevCommand extends BaseCommand {

  public function composerAction($argv){
    if(count($argv) > 0){
      foreach($argv as $dependency){
        $this->composer($dependency);
      }
    }
  }

  protected function composer($dependency = null){
    $composer = $this->Path . '/composer';
    if(is_file($composer)){
      try {
        if($dependency == null){
          shell_exec('composer update');
        } else {
          shell_exec('composer require ' . $dependency);
        }
        return true;
      } catch (Error $e) {
        $this->error($e->getMessage().'Internal error');
      }
    }
    return false;
  }
}
