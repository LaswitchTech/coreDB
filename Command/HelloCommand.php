<?php

//Import BaseCommand class into the global namespace
use LaswitchTech\phpCLI\BaseCommand;

class HelloCommand extends BaseCommand {

  public function worldAction($argv) {
    if(count($argv) > 0){
      foreach($argv as $name){
        $this->info('Hello ' . $name . '!');
      }
    } else {
      $this->success('Hello World!');
    }
  }

  public function askAction($argv) {
    $answer = $this->input('What is your name?');
    $this->warning($answer);
    $answer = $this->input('What is your name?', 'John Doe');
    $this->warning($answer);
    $answer = $this->input('Are you a?',['dog','cat','person']);
    $this->warning($answer);
    $answer = $this->input('Are you a?',['dog','cat','person'],'person');
    $this->warning($answer);
    $answer = $this->input('What kind of person are you?',5);
    $this->warning($answer);
    $answer = $this->input('What kind of person are you?',0);
    $this->warning($answer);
    $this->input('What kind of person are you?',5,true);
    $this->warning($answer);
  }
}
