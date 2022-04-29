<?php

class Notification {

  protected $Auth = false;
  protected $Debug = false;
  protected $Types = ['application','email'];
  protected $Defaults = [];
  protected $Notifications = [];

  public function __construct($auth){
    $this->Auth = $auth;
    $this->Debug = $this->Auth->Debug;
  }

  public function add($name, $types = ['application','email'], $value = true){
    if(is_array($name)){
      if(isset($name['value'])){ $value = $name['value']; }
      if(isset($name['types'])){ $types = $name['types']; }
      if(isset($name['name'])){ $name = $name['name']; }
    }
    if(!is_array($types) && in_array($types,$this->Types)){ $types = [$types]; }
    foreach($this->Types as $type){
      if(in_array($type,$types)){ $this->Defaults[$name][$type] = $value; }
      else { $this->Defaults[$name][$type] = false; }
    }
  }

  protected function isReady(){
    return ($this->Auth && $this->Auth->isLogin()) || ($this->Auth && defined('STDIN'));
  }

  protected function isAssoc(array $arr){
    if (array() === $arr) return false;
    return array_keys($arr) !== range(0, count($arr) - 1);
  }

  protected function setUser($id){
    if($this->Auth->isLogin() && $id == $this->Auth->User['id']){
      $user['user'] = $this->Auth->User;
      $user['groups'] = $this->Auth->Groups;
      $user['roles'] = $this->Auth->Roles;
      $user['permissions'] = $this->Auth->Permissions;
      $user['options'] = $this->Auth->Options;
    } else { $user = $this->Auth->getUser($id); }
    $this->Notifications = $this->Defaults;
    if(isset($user['options']['notifications'])){
      foreach($user['options']['notifications'] as $kind => $notif){
        foreach($notif as $name => $default){
          if(isset($this->Notifications[$name][$kind])){ $this->Notifications[$name][$kind] = $default; }
        }
      }
    }
    return $user;
  }

  protected function validate($notification, $type = 'application'){
    return $this->Notifications[$notification['name']][$type];
  }

  public function getSettings($user){
    $this->setUser($user);
    return $this->Notifications;
  }

  public function create($data = []){
    if($this->isReady()){
      $notification = [
        'name' => 'unknown',
        'user' => 0,
        'icon' => 'fa-solid fa-info',
        'subject' => 'New Notification',
        'body' => 'New Notification',
        'trigger' => '',
        'meta' => null,
      ];
      if($this->Auth->isLogin()){ $notification['user'] = $this->Auth->User['id']; }
      if($this->Debug){ $notification['trigger'] = 'debug'; }
      foreach($data as $key => $value){ if(isset($notification[$key])){ $notification[$key] = $value; }}
      $result = [];
      $user = $this->setUser($notification['user']);
      if($this->validate($notification)){
        $result['application'] = $this->Auth->SQL->database->create(['notifications' => $notification]);
        $result['application'] = $result['application'][array_key_first($result['application'])];
        $result['application'] = $result['application'][array_key_first($result['application'])];
      }
      if($this->validate($notification, 'email')){
        $body = 'Dear'.' '.$user['user']['name'].',<br>';
        $body .= $notification['body'].',<br>';
        $extra = [
          'title' => $notification['subject'],
          'subject' => $notification['subject'],
        ];
        if($this->Auth->SMTP->send($user['user']['username'],$body,$extra)){ $result['email'] = $notification; }
      }
      return $result;
    } else { return false; }
  }

  public function read($id = null){
    if($this->isReady()){
      if($id == null){
        if($this->Auth->isLogin()){
          $statement = $this->Auth->SQL->database->prepare('select','notifications',['conditions' => ['user' => '=', 'isRead' => '=']]);
          return $this->Auth->SQL->database->query($statement,[$this->Auth->User['id'],0])->fetchAll();
        } else {
          $statement = $this->Auth->SQL->database->prepare('select','notifications',['conditions' => ['isRead' => '=']]);
          return $this->Auth->SQL->database->query($statement,0)->fetchAll();
        }
      } else {
        $run = function($id){
          $statement = $this->Auth->SQL->database->prepare('select','notifications',['conditions' => ['id' => '=']]);
          $notification = $this->Auth->SQL->database->query($statement,$id)->fetchAll();
          if(count($notification) > 0){
            $notification = $notification[0];
            $notification['isRead'] = 1;
            $statement = $this->Auth->SQL->database->prepare('update','notifications',$notification,['conditions' => ['id' => '=']]);
            $values = array_values($notification);
            array_push($values,$notification['id']);
            $this->Auth->SQL->database->query($statement,$values);
            return $notification;
          }
        };
        if(is_array($id)){
          $return = [];
          if($this->isAssoc($id)){ return false; }
          foreach($id as $unique){
            $unique = intval($unique);
            if(is_int($unique) && $unique != 0){ array_push($return,$run($unique)); }
          }
          return $return;
        } else { return $run($id); }
      }
    } else { return false; }
  }
}
