<?php

class Notification {

  protected $Auth = false;
  protected $Debug = false;

  public function __construct($auth){
    $this->Auth = $auth;
    $this->Debug = $this->Auth->Debug;
  }

  protected function isReady(){
    return ($this->Auth && $this->Auth->isLogin()) || ($this->Auth && defined('STDIN'));
  }

  public function create($data = []){
    if($this->isReady()){
      $notification = [
        'icon' => 'fas fa-info',
        'user' => 0,
        'text' => 'New Notification',
        'trigger' => '',
        'meta' => null,
      ];
      if($this->Auth->isLogin()){ $notification['user'] = $this->Auth->User['id']; }
      if($this->Debug){ $notification['trigger'] = 'Engine.Debug.action'; }
      foreach($data as $key => $value){ if(isset($notification[$key])){ $notification[$key] = $value; }}
      $result = $this->Auth->SQL->database->create(['notifications' => $notification]);
      $result = $result[array_key_first($result)];
      $result = $result[array_key_first($result)];
      return $result;
    } else { return false; }
  }

  public function read($id = null){
    if($this->isReady()){
      if($id == null){
        if($this->Auth->isLogin()){
          $statement = $this->Auth->SQL->database->prepare('select','notifications',['conditions' => ['user' => '=']]);
          return $this->Auth->SQL->database->query($statement,$this->Auth->User['id'])->fetchAll();
        } else {
          $statement = $this->Auth->SQL->database->prepare('select','notifications');
          return $this->Auth->SQL->database->query($statement)->fetchAll();
        }
      } else {
        $statement = $this->Auth->SQL->database->prepare('select','notifications',['conditions' => ['id' => '=']]);
        $notification = $this->Auth->SQL->database->query($statement,$id)->fetchAll()[0];
        $notification['isRead'] = 1;
        $statement = $this->Auth->SQL->database->prepare('update','notifications',$notification,['conditions' => ['id' => '=']]);
        $values = array_values($notification);
        array_push($values,$notification['id']);
        $this->Auth->SQL->database->query($statement,$values);
        return $notification;
      }
    } else { return false; }
  }
}
