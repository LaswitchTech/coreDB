<?php

class Option {

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

  public function save($data = []){
    $return = [];
    if($this->isReady()){
      if(is_array($data) && $this->isAssoc($data)){
        foreach($data as $name => $values){
          $option = ['name' => $name,'options' => $values];
          $query = ['options' => $option];
          if(isset($this->Auth->Options[$name])){
            $relations = $this->Auth->SQL->database->getRelationshipsTo('options',$this->Auth->Options[$name]['id']);
            if(isset($relations['users'][$this->Auth->User['id']])){
              $query['options']['id'] = $this->Auth->Options[$name]['id'];
              $return[$name] = $this->Auth->SQL->database->update($query);
            } else {
              $new = $this->Auth->SQL->database->create($query);
              foreach($new['options'] as $id => $newOption){ $option['id'] = $id;break; }
              $this->Auth->SQL->database->createRelationship(['users' => $this->Auth->User['id']],['options' => $option['id']]);
              $return[$name] = $option;
            }
          } else {
            $new = $this->Auth->SQL->database->create($query);
            foreach($new['options'] as $id => $newOption){ $option['id'] = $id;break; }
            $this->Auth->SQL->database->createRelationship(['users' => $this->Auth->User['id']],['options' => $option['id']]);
            $return[$name] = $option;
          }
        }
        return $return;
      } else { return false; }
    } else { return false; }
  }
}
