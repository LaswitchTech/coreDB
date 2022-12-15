<?php

//Import BaseCommand class into the global namespace
use LaswitchTech\phpCLI\BaseCommand;

class ApiCommand extends BaseCommand {

  public function getAction($argv){
    $this->warning(json_encode($_SERVER,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    $this->info(json_encode($argv,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
  }

  public function postAction($argv){}
}
