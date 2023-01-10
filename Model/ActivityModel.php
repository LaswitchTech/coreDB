<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

class ActivityModel extends BaseModel {

  public function getActivities($owner, $limit = 0, $filters = []) {
    $ownerTable = array_key_first($owner);
    $ownerID = $owner[$ownerTable];
    if(is_int($ownerID)){
      $ownerArray = $this->select('SELECT * FROM `' . $ownerTable . '` WHERE id = ?',[$ownerID]);
      if(count($ownerArray) > 0){
        $ownerRecord = [];
        if(isset($ownerArray[0]['username'])){ $ownerRecord[$ownerTable] = $ownerArray[0]['username']; }
        if(isset($ownerArray[0]['name'])){ $ownerRecord[$ownerTable] = $ownerArray[0]['name']; }
        if(count($ownerRecord) > 0){ $owner = $ownerRecord; }
      }
    }
    $statement = 'SELECT * FROM activities WHERE owner = ?';
    $values = [];
    array_push($values,json_encode($owner, JSON_UNESCAPED_SLASHES));
    if(is_array($filters) && count($filters) > 0){
      $statement .= ' AND (';
      foreach($filters as $filter){
        if(count($values) > 1){ $statement .= ' OR'; }
        $statement .= ' sharedTo LIKE ?';
        array_push($values,'%'.json_encode($filter, JSON_UNESCAPED_SLASHES).'%');
      }
      $statement .= ')';
    }
    $statement .= ' ORDER BY id DESC';
    if($limit > 0){
      $statement .= ' LIMIT ?';
      array_push($values,$limit);
    }
    return $this->select($statement, $values);
  }

  public function addActivity($owner, $options){
    $ownerTable = array_key_first($owner);
    $ownerID = $owner[$ownerTable];
    if(is_int($ownerID)){
      $ownerArray = $this->select('SELECT * FROM `' . $ownerTable . '` WHERE id = ?',[$ownerID]);
      if(count($ownerArray) > 0){
        $ownerRecord = [];
        if(isset($ownerArray[0]['username'])){ $ownerRecord[$ownerTable] = $ownerArray[0]['username']; }
        if(isset($ownerArray[0]['name'])){ $ownerRecord[$ownerTable] = $ownerArray[0]['name']; }
        if(count($ownerRecord) > 0){ $owner = $ownerRecord; }
      }
    }
    $header = null;
    $body = null;
    $footer = null;
    $route = null;
    $icon = 'activity';
    $color = 'secondary';
    $sharedTo = [];
    $callback = null;
    if(isset($options['header'])){ $header = $options['header']; }
    if(isset($options['body'])){ $body = $options['body']; }
    if(isset($options['footer'])){ $footer = $options['footer']; }
    if(isset($options['route'])){ $route = $options['route']; }
    if(isset($options['icon'])){ $icon = $options['icon']; }
    if(isset($options['color'])){ $color = $options['color']; }
    if(isset($options['sharedTo'])){ $sharedTo = $options['sharedTo']; }
    if(isset($options['callback'])){ $callback = $options['callback']; }
    foreach($sharedTo as $key => $record){
      $recordTable = array_key_first($record);
      $recordID = $record[$recordTable];
      if(is_int($recordID)){
        $recordArray = $this->select('SELECT * FROM `' . $recordTable . '` WHERE id = ?',[$recordID]);
        if(count($recordArray) > 0){
          $recordRecord = [];
          if(isset($recordArray[0]['username'])){ $recordRecord[$recordTable] = $recordArray[0]['username']; }
          if(isset($recordArray[0]['name'])){ $recordRecord[$recordTable] = $recordArray[0]['name']; }
          if(count($recordRecord) > 0){ $sharedTo[$key] = $recordRecord; }
        }
      }
    }
    return $this->insert("INSERT INTO activities (header, body, footer, route, owner, icon, color, sharedTo, callback) VALUES (?,?,?,?,?,?,?,?,?)", [$header, $body, $footer, $route, json_encode($owner,JSON_UNESCAPED_SLASHES), $icon, $color, json_encode($sharedTo,JSON_UNESCAPED_SLASHES), $callback]);
  }
}
