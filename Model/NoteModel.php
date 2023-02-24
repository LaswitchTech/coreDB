<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

//Import Configurator class into the global namespace
use LaswitchTech\coreDB\Configurator;

class NoteModel extends BaseModel {

  protected $Configurator = null;
  protected $Keys = [];

  public function __construct(){

    // Setup Configurator
    $this->Configurator = new Configurator();

    // Load Parent Constructor
    $return = parent::__construct();

    // Return
    return $return;
  }

  public function new($array = []){
    $required = ['content'];
    $columns = ['id','created','modified','content','owner','sharedTo','linkTo','extra'];
    $keys = [];
    $values = [];
    $marks = [];
    foreach($array as $key => $value){
      if(is_array($value)){ $value = json_encode($value,JSON_UNESCAPED_SLASHES); }
      if(in_array($key,$columns)){
        $keys[] = $key;
        $values[] = $value;
        $marks[] = '?';
      }
      if(($k = array_search($key, $required)) !== false) {
        unset($required[$k]);
        $required = array_values($required);
      }
    }
    if(count($required) <= 0){
      $statement = "INSERT INTO notes (" . implode(',',$keys) . ") VALUES (" . implode(',',$marks) . ")";
      $id = $this->insert($statement,$values);
      if($id){
        return $this->get(['id' => $id]);
      }
    }
  }

  public function get($array = []){
    if(isset($array['id']) && (is_string($array['id']) || is_int($array['id'])) && !in_array($array['id'],[0,'false','true'])){
      $key = 'id';
    } else {
      if(isset($array['linkTo'])){
        $key = 'linkTo';
      }
    }
    if(isset($key)){
      $value = $array[$key];
      $values = [];
      if(is_array($value)){ $value = json_encode($value,JSON_UNESCAPED_SLASHES); }
      $values[] = $value;
      $statement = "SELECT * FROM notes WHERE " . $key . " = ?";
      if(isset($array['sharedTo'])){
        if(!is_array($array['sharedTo'])){ $array['sharedTo'] = json_decode($value,true); }
        if(is_array($array['sharedTo']) && count($array['sharedTo']) > 0){
          $statement .= ' AND (';
          foreach($array['sharedTo'] as $object){
            if(is_array($object)){ $object = json_encode($object,JSON_UNESCAPED_SLASHES); }
            if(substr($statement, -1) === '?'){ $statement .= ' OR'; }
            $statement .= ' `sharedTo` LIKE ?';
            $values[] = '%'.strval($object).'%';
          }
          $statement .= ')';
        }
      }
      $notes = $this->select($statement, $values);
      if(count($notes) > 0){
        $note = $notes[0];
        foreach(['owner','sharedTo','linkTo','extra'] as $key){
          if($note[$key] != null){ $note[$key] = json_decode($note[$key],true); }
        }
        return $note;
      }
    }
  }

  public function save($array = []){
    $required = ['id','content'];
    $columns = ['id','created','modified','content','owner','sharedTo','linkTo','extra'];
    $keys = [];
    $values = [];
    foreach($array as $key => $value){
      if(is_array($value)){ $value = json_encode($value,JSON_UNESCAPED_SLASHES); }
      if(in_array($key,$columns)){
        $keys[] = $key . " = ?";
        $values[] = $value;
      }
      if(($k = array_search($key, $required)) !== false) {
        unset($required[$k]);
        $required = array_values($required);
      }
    }
    if(count($required) <= 0){
      $values[] = $array['id'];
      $statement = "UPDATE notes SET " . implode(',',$keys) . " WHERE id = ?";
      $affected = $this->update($statement,$values);
      if($affected){
        return $this->get(['id' => $array['id']]);
      }
    }
  }

  public function remove($array = []){
    if(isset($array['id'])){
      return $this->delete("DELETE FROM notes WHERE id = ?", [$array['id']]);
    }
  }
}
