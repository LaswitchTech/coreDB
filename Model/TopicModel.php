<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

//Import Configurator class into the global namespace
use LaswitchTech\coreDB\Configurator;

class TopicModel extends BaseModel {

  protected $Configurator = null;

  public function __construct(){

    // Setup Configurator
    $this->Configurator = new Configurator();

    // Load Parent Constructor
    $return = parent::__construct();

    // Return
    return $return;
  }

  protected function split($string, $seperator = ':'){
    $array = [];
    if(is_string($seperator)){
      $parts = explode($seperator,$string);
      if(count($parts) > 1){
        $key = $parts[0];
        unset($parts[0]);
        $array[$key] = [];
        foreach($parts as $part){
          array_push($array[$key],trim($part));
        }
      } else {
        return $string;
      }
    }
    if(count($array) <= 0){ $array = [$string]; }
    return $array;
  }

  public function parseMeta($meta = [], $topicSeperators = [':','#','/','|','='], $referenceSeperators = [',',' ',';']){
    $splitter = '+';
    if(!in_array(end($referenceSeperators),$topicSeperators) && !in_array(end($referenceSeperators),$topicSeperators)){ $splitter = end($referenceSeperators); }
    if(is_string($meta)){ json_decode($meta,true); }
    if(!is_array($meta)){ $meta = []; }
    $array = $meta;
    foreach($meta as $key => $values){
      foreach($values as $valueKey => $value){
        $split = [];
        foreach($topicSeperators as $topicSeperator){
          $split = $this->split($value,$topicSeperator);
          if(is_array($split)){ break; }
        }
        if(is_array($split) && count($split) > 0){
          unset($array[$key][$valueKey]);
          if(count($array[$key]) < 1){
            unset($array[$key]);
          }
          $arrayKey = array_key_first($split);
          $arrayValue = $split[$arrayKey];
          $arrayValue = implode($splitter,$arrayValue);
          foreach($referenceSeperators as $referenceSeperator){
            $arrayValue = str_replace($referenceSeperator,$splitter,$arrayValue);
          }
          $arrayValues = explode($splitter,$arrayValue);
          if(!isset($array[$arrayKey])){ $array[$arrayKey] = []; }
          foreach($arrayValues as $arrayValue){
            if(!in_array($arrayValue,$array[$arrayKey])){
              $array[$arrayKey][] = $arrayValue;
            }
            if(isset($array['OTHER']) && is_array($array['OTHER'])){
              if(($otherKey = array_search($arrayValue, $array['OTHER'])) !== false) {
                unset($array['OTHER'][$otherKey]);
              }
            }
          }
        }
      }
    }
    return $array;
  }

  public function mergeArray($array1 = [], $array2 = []){
    if(is_array($array1) && is_array($array2)){
      foreach($array2 as $key => $values){
        if(!isset($array1[$key])){
          $array1[$key] = $values;
        } else {
          foreach($values as $value){
            if(!in_array($value,$array1[$key])){
              $array1[$key][] = $value;
            }
          }
        }
      }
      return $array1;
    }
    return [];
  }

  public function dataset($record){
    $dataset = [];
    if(isset($record['dataset'])){
      $dataset = $this->mergeArray($dataset,$record['dataset']);
    }
    if(isset($record['meta'])){
      $dataset = $this->mergeArray($dataset,$record['meta']);
    }
    return $dataset;
  }

  public function sharedTo($record){
    $dataset = [];
    if(isset($record['sharedTo'])){
      $dataset = $record['sharedTo'];
    }
    if(isset($record['account'])){
      if(!in_array(['users' => $record['account']],$dataset)){
        $users = $this->select("SELECT * FROM auth_users WHERE username = ?", [$record['account']]);
        if(count($users) > 0){
          $dataset[] = ['users' => $users[0]['username']];
        }
      }
    }
    if(isset($record['sender'])){
      if(!in_array(['users' => $record['sender']],$dataset)){
        $users = $this->select("SELECT * FROM auth_users WHERE username = ?", [$record['sender']]);
        if(count($users) > 0){
          $dataset[] = ['users' => $users[0]['username']];
        }
      }
    }
    if(isset($record['from'])){
      if(!in_array(['users' => $record['from']],$dataset)){
        $users = $this->select("SELECT * FROM auth_users WHERE username = ?", [$record['from']]);
        if(count($users) > 0){
          $dataset[] = ['users' => $users[0]['username']];
        }
      }
    }
    if(isset($record['to'])){
      foreach($record['to'] as $value){
        if(!in_array(['users' => $value],$dataset)){
          $users = $this->select("SELECT * FROM auth_users WHERE username = ?", [$value]);
          if(count($users) > 0){
            $dataset[] = ['users' => $users[0]['username']];
          }
        }
      }
    }
    if(isset($record['cc'])){
      foreach($record['cc'] as $value){
        if(!in_array(['users' => $value],$dataset)){
          $users = $this->select("SELECT * FROM auth_users WHERE username = ?", [$value]);
          if(count($users) > 0){
            $dataset[] = ['users' => $users[0]['username']];
          }
        }
      }
    }
    if(isset($record['bcc'])){
      foreach($record['bcc'] as $value){
        if(!in_array(['users' => $value],$dataset)){
          $users = $this->select("SELECT * FROM auth_users WHERE username = ?", [$value]);
          if(count($users) > 0){
            $dataset[] = ['users' => $users[0]['username']];
          }
        }
      }
    }
    return $dataset;
  }

  public function getTopics($filters = [], $limit = null){
    $values = [];
    $statement = "SELECT * FROM topics_topics";
    $columns = [
      'id',
      'created',
      'modified',
      'meta',
      'dataset',
      'emls',
      'mids',
      'files',
      'contacts',
      'sharedTo',
      'countUnread',
    ];
    if(is_array($filters) && count($filters) > 0){
      foreach($filters as $column => $value){
        if(in_array($column,$columns)){
          if(is_array($value)){ $value = json_encode($value,JSON_UNESCAPED_SLASHES); }
          if(strpos($statement, 'WHERE') === false){ $statement .= " WHERE"; }
          if(substr($statement, -1) === '?'){ $statement .= ' AND'; }
          switch($column){
            case "meta":
            case "dataset":
            case "emls":
            case "mids":
            case "files":
            case "contacts":
            case "sharedTo":
              $statement .= ' `' . $column . '` LIKE ?';
              array_push($values,'%"'.$value.'"%');
              break;
            default:
              $statement .= ' `' . $column . '` = ?';
              array_push($values,$value);
              break;
          }
        }
      }
    }
    $statement .= ' ORDER BY id DESC';
    if($limit != null){
      if(!is_string($limit) && !is_int($limit)){ $limit = null; }
      if(is_string($limit)){ $limit = intval($limit); }
    }
    if($limit != null){
      $statement .= ' LIMIT ?';
      array_push($values,$limit);
    }
    $emls = $this->select($statement, $values);
    foreach($emls as $key => $eml){
      if(isset($eml['meta'])){ $eml['meta'] = json_decode($eml['meta'],true); }
      if(isset($eml['dataset'])){ $eml['dataset'] = json_decode($eml['dataset'],true); }
      if(isset($eml['emls'])){ $eml['emls'] = json_decode($eml['emls'],true); }
      if(isset($eml['mids'])){ $eml['mids'] = json_decode($eml['mids'],true); }
      if(isset($eml['files'])){ $eml['files'] = json_decode($eml['files'],true); }
      if(isset($eml['contacts'])){ $eml['contacts'] = json_decode($eml['contacts'],true); }
      if(isset($eml['sharedTo'])){ $eml['sharedTo'] = json_decode($eml['sharedTo'],true); }
      $emls[$key] = $eml;
    }
    return $emls;
  }

  public function updateTopic($topic = []){
    $values = [];
    $statement = "UPDATE topics_topics SET";
    $columns = [
      'meta',
      'dataset',
      'emls',
      'mids',
      'files',
      'contacts',
      'sharedTo',
      'countUnread',
    ];
    if(is_array($topic) && count($topic) > 0){
      if(isset($topic['id'])){
        foreach($topic as $column => $value){
          if(in_array($column,$columns)){
            if(is_array($value)){ $value = json_encode($value,JSON_UNESCAPED_SLASHES); }
            if(substr($statement, -3) !== 'SET'){ $statement .= ','; }
            $statement .= ' `' . $column . '` = ?';
            array_push($values,$value);
          }
        }
        $statement .= ' WHERE id = ?';
        array_push($values,$topic['id']);
        return $this->update($statement, $values);
      }
    }
    return null;
  }

  public function addTopic($topic){
    $placeholders = '';
    $fields = '';
    $values = [];
    $columns = [
      'meta',
      'dataset',
      'emls',
      'mids',
      'files',
      'contacts',
      'sharedTo',
      'countUnread',
    ];
    $allRequired = true;
    foreach($columns as $column){
      if($topic[$column] == null){ $topic[$column] = []; }
      if(isset($topic[$column])){
        if(substr($placeholders, -1) === '?'){ $placeholders .= ','; }
        $placeholders .= '?';
        if($fields !== ''){ $fields .= ','; }
        $fields .= $column;
        if(is_array($topic[$column])){
          $topic[$column] = json_encode($topic[$column],JSON_UNESCAPED_SLASHES);
        }
        $values[] = $topic[$column];
      } else {
        $allRequired = false;
      }
    }
    if($allRequired){
      $statement = "INSERT INTO topics_topics (" . $fields . ") VALUES (" . $placeholders . ")";
      return $this->insert($statement, $values);
    }
    return $allRequired;
  }
}
