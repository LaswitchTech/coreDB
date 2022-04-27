<?php

class Helper {

  protected $Auth = false;
  protected $Debug = false;

  public function __construct($auth){
    $this->Auth = $auth;
    $this->Debug = $this->Auth->Debug;
  }

  protected function isReady(){
    return ($this->Auth && $this->Auth->isLogin()) || ($this->Auth && defined('STDIN'));
  }

  protected function isAssoc(array $arr){
    if (array() === $arr) return false;
    return array_keys($arr) !== range(0, count($arr) - 1);
  }

  public function forms($name){
    $forms = ["create" => [],"update" => []];
    $tables = $this->Auth->SQL->database->getTables();
    if(property_exists($this, $name) && method_exists($this->$name, 'forms')){
      $forms = $this->$name->forms($name);
    } elseif(in_array($name,$tables)){
      $headers = $this->Auth->SQL->database->getHeaders($name);
      $primary = $this->Auth->SQL->database->getPrimary($name);
      foreach($headers as $header){
        $input = [
          "icon" => "fa-solid fa-keyboard",
          "value" => null,
          "component" => "input",
          "type" => "text",
          "label" => $header,
          "translate" => true,
          "list" => [],
          "hidden" => false,
        ];
        if($header != $primary){
          $forms['create'][$header] = $input;
          $forms['update'][$header] = $input;
        } else {
          $forms['update'][$header] = $input;
          $forms['update'][$header]['hidden'] = true;
        }
      }
    }
    return $forms;
  }
}
