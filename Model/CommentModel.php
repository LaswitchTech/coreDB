<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

//Import Configurator class into the global namespace
use LaswitchTech\coreDB\Configurator;

class CommentModel extends BaseModel {

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
    $columns = ['id','created','modified','content','likes','owner','linkTo','extra'];
    $keys = [];
    $values = [];
    $marks = [];
    if(!isset($array['likes']) || $array['likes'] == null){ $array['likes'] = []; }
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
      $statement = "INSERT INTO comments (" . implode(',',$keys) . ") VALUES (" . implode(',',$marks) . ")";
      $id = $this->insert($statement,$values);
      if($id){
        return $this->get(['id' => $id])[0];
      }
    }
  }

  public function get($array = []){
    if(isset($array['id']) && (is_string($array['id']) || is_int($array['id'])) && !in_array($array['id'],[0,'false','true','null',null])){
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
      $statement = "SELECT * FROM comments WHERE " . $key . " = ?";
      if(isset($array['likes'])){
        if(!is_array($array['likes'])){ $array['likes'] = json_decode($value,true); }
        if(is_array($array['likes']) && count($array['likes']) > 0){
          $statement .= ' AND (';
          foreach($array['likes'] as $object){
            if(is_array($object)){ $object = json_encode($object,JSON_UNESCAPED_SLASHES); }
            if(substr($statement, -1) === '?'){ $statement .= ' OR'; }
            $statement .= ' `likes` LIKE ?';
            $values[] = '%'.strval($object).'%';
          }
          $statement .= ')';
        }
      }
      $comments = $this->select($statement, $values);
      if(count($comments) > 0){
        foreach($comments as $id => $comment){
          foreach(['owner','likes','linkTo','extra'] as $key){
            if($comments[$id][$key] != null){ $comments[$id][$key] = json_decode($comments[$id][$key],true); }
          }
        }
        return $comments;
      }
    }
  }

  public function save($array = []){
    $required = ['id','content'];
    $columns = ['id','created','modified','content','owner','likes','linkTo','extra'];
    $keys = [];
    $values = [];
    if(!isset($array['likes']) || $array['likes'] == null){ $array['likes'] = []; }
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
      $statement = "UPDATE comments SET " . implode(',',$keys) . " WHERE id = ?";
      $affected = $this->update($statement,$values);
      if($affected){
        return $this->get(['id' => $array['id']])[0];
      }
    }
  }

  public function remove($array = []){
    if(isset($array['id'])){
      return $this->delete("DELETE FROM comments WHERE id = ?", [$array['id']]);
    }
  }

  public function like($id, $user){
    $comment = $this->get(['id' => $id]);
    if(count($comment) > 0){
      $comment = $comment[0];
      if(($key = array_search($user, $comment['likes'])) !== false) {
        unset($comment['likes'][$key]);
        $comment['likes'] = array_values($comment['likes']);
      } else {
        $comment['likes'][] = $user;
      }
      return $this->save($comment);
    }
  }
}
