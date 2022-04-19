<?php

// Import Librairies
require_once dirname(__FILE__,3) . '/src/lib/api.php';

class CRUD extends API {

  public function create($data = []){
    if($this->Auth->isLogin() && $this->Auth->isActivated() && !$this->Auth->isDeactivated()){
      $return['output'] = $this->Auth->SQL->database->create($data);
      if(!empty($return['output'])){ $return['success'] = $this->getField('Records Created'); }
      else { $return['error'] = $this->getField('Unable to create records'); }
      return $return;
    }
  }

  public function read($data = []){
    if($this->Auth->isLogin() && $this->Auth->isActivated() && !$this->Auth->isDeactivated()){
      $return['output'] = $this->Auth->SQL->database->read($data);
      $return['success'] = $this->getField('Records Retrieved');
      return $return;
    }
  }

  public function update($data = []){
    if($this->Auth->isLogin() && $this->Auth->isActivated() && !$this->Auth->isDeactivated()){
      $return['output'] = $this->Auth->SQL->database->update($data);
      if(!empty($return['output'])){ $return['success'] = $this->getField('Records Updated'); }
      else { $return['error'] = $this->getField('Unable to update records'); }
      return $return;
    }
  }

  public function delete($data = []){
    if($this->Auth->isLogin() && $this->Auth->isActivated() && !$this->Auth->isDeactivated()){
      $return['output'] = $this->Auth->SQL->database->delete($data);
      if(!empty($return['output'])){ $return['success'] = $this->getField('Records Deleted'); }
      else { $return['error'] = $this->getField('Unable to delete records'); }
      return $return;
    }
  }

  public function headers($data){
    if($this->Auth->isLogin() && $this->Auth->isActivated() && !$this->Auth->isDeactivated()){
      $return['output'] = $this->Auth->SQL->database->getHeaders($data);
      $return['success'] = $this->getField('Headers Retrieved');
      return $return;
    }
  }

  public function search($search){
    if(($search != '' || $search == 0) && $this->Auth->isLogin() && $this->Auth->isActivated() && !$this->Auth->isDeactivated()){
      $search = '%'.strtoupper($search).'%';
      $tables = $this->Auth->SQL->database->getTables();
      foreach($tables as $table){
        $conditions = [];
        $parameters = [];
        $headers = $this->Auth->SQL->database->getHeaders($table);
        foreach($headers as $header){
          $condition = [];
          $condition[$header] = "LIKE";
          array_push($conditions,$condition);
          array_push($parameters,$search);
        }
        $statement = $this->Auth->SQL->database->prepare('select',$table, ['conditions' => $conditions, 'operation' => 'or']);
        $query = $this->Auth->SQL->database->query($statement,$parameters)->fetchAll();
        if(count($query) > 0){ $return['output'][$table] = $query; }
      }
      $return['success'] = $this->getField('Search Completed');
      return $return;
    }
  }
}
