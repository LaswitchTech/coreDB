<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

//Import Configurator class into the global namespace
use LaswitchTech\coreDB\Configurator;

class TopicModel extends BaseModel {

  protected $Configurator = null;
  protected $Keys = [];

  public function __construct(){

    // Setup Configurator
    $this->Configurator = new Configurator();

    // Load Parent Constructor
    $return = parent::__construct();

    // Load Unique Dataset Keys
    $this->loadUniqueDatasetKeys();

    // Return
    return $return;
  }

  protected function loadUniqueDatasetKeys(){
    $rows = $this->select("SELECT * FROM topics_unique_dataset_keys", []);
    foreach($rows as $row){
      $this->Keys[] = $row['name'];
    }
  }

  public function getUniqueKeys(){
    return $this->Keys;
  }

  public function addUniqueKey($keys){
    if(!is_array($keys)){ $keys = [$keys]; }
    foreach($keys as $key){
      if(is_string($key)){
        $statement = "INSERT INTO topics_unique_dataset_keys (name) VALUES (?)";
        $this->insert($statement, [strtoupper($key)]);
      }
    }
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
    foreach($dataset as $key => $values){
      if(is_numeric($key)){
        unset($dataset[$key]);
      } else {
        if(in_array($key,['OTHER'])){
          unset($dataset[$key]);
        }
      }
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

  public function getComments($topic){
    $records = [];
    $comments = $this->select("SELECT * FROM topics_comments WHERE topic = ? ORDER BY id ASC", [$topic]);
    foreach($comments as $key => $comment){
      if(isset($comment['linkTo'])){ $comment['linkTo'] = json_decode($comment['linkTo'],true); }
      if(isset($comment['owner'])){ $comment['owner'] = json_decode($comment['owner'],true); }
      $records[$comment['id']] = $comment;
    }
    return $records;
  }

  public function addComment($topic = null, $content = null, $owner = null, $linkTo = null){
    $comment = [];
    if(is_string($topic)){ $comment['topic'] = $topic; }
    if(is_string($content)){ $comment['content'] = $content; }
    if(is_string($owner)){ $comment['owner'] = $owner; }
    if(is_string($linkTo)){ $comment['linkTo'] = $linkTo; }
    if(is_array($topic)){ $comment = $topic; }
    if(is_array($comment) && isset($comment['topic'],$comment['content'],$comment['owner'])){
      if(!isset($comment['linkTo'])){ $comment['linkTo'] = null; }
      if(is_array($comment['topic'])){ $comment['topic'] = json_encode($comment['topic'],JSON_UNESCAPED_SLASHES); }
      if(is_array($comment['content'])){ $comment['content'] = json_encode($comment['content'],JSON_UNESCAPED_SLASHES); }
      if(is_array($comment['owner'])){ $comment['owner'] = json_encode($comment['owner'],JSON_UNESCAPED_SLASHES); }
      if(is_array($comment['linkTo'])){ $comment['linkTo'] = json_encode($comment['linkTo'],JSON_UNESCAPED_SLASHES); }
      $commentID = $this->insert("INSERT INTO topics_comments (topic,content,owner,linkTo) VALUES (?,?,?,?)", [$comment['topic'],$comment['content'],$comment['owner'],$comment['linkTo']]);
      if($commentID){
        $comments = $this->select("SELECT * FROM topics_comments WHERE id = ? ORDER BY id ASC", [$commentID]);
        if(count($comments) > 0){
          return $comments[0];
        }
      }
    }
  }

  public function addNote($topic = null, $content = null, $owner = null, $sharedTo = null, $linkTo = null){
    $note = [];
    if(is_string($topic)){ $note['topic'] = $topic; }
    if(is_string($content)){ $note['content'] = $content; }
    if(is_string($owner)){ $note['owner'] = $owner; }
    if(is_string($sharedTo)){ $note['sharedTo'] = $sharedTo; }
    if(is_string($linkTo)){ $note['linkTo'] = $linkTo; }
    if(is_array($topic)){ $note = $topic; }
    if(is_array($note) && isset($note['topic'],$note['content'],$note['owner'])){
      if(!isset($note['linkTo'])){ $note['linkTo'] = null; }
      if(is_array($note['topic'])){ $note['topic'] = json_encode($note['topic'],JSON_UNESCAPED_SLASHES); }
      if(is_array($note['content'])){ $note['content'] = json_encode($note['content'],JSON_UNESCAPED_SLASHES); }
      if(is_array($note['owner'])){ $note['owner'] = json_encode($note['owner'],JSON_UNESCAPED_SLASHES); }
      if(is_array($note['sharedTo'])){ $note['sharedTo'] = json_encode($note['sharedTo'],JSON_UNESCAPED_SLASHES); }
      if(is_array($note['linkTo'])){ $note['linkTo'] = json_encode($note['linkTo'],JSON_UNESCAPED_SLASHES); }
      $noteID = $this->insert("INSERT INTO topics_notes (topic,content,owner,sharedTo,linkTo) VALUES (?,?,?,?,?)", [$note['topic'],$note['content'],$note['owner'],$note['sharedTo'],$note['linkTo']]);
      if($noteID){
        $notes = $this->select("SELECT * FROM topics_notes WHERE id = ? ORDER BY id ASC", [$noteID]);
        if(count($notes) > 0){
          return $notes[0];
        }
      }
    }
  }

  public function getNotes($topic, $owners = []){
    $records = [];
    $values = [];
    $statement = "SELECT * FROM topics_notes";
    if(is_array($owners) && count($owners) > 0){
      if(strpos($statement, 'WHERE') === false){ $statement .= " WHERE ("; }
      foreach($owners as $owner){
        if(is_array($owner)){ $owner = json_encode($owner,JSON_UNESCAPED_SLASHES); }
        if(substr($statement, -1) === '?'){ $statement .= ' OR'; }
        $statement .= ' `sharedTo` LIKE ?';
        array_push($values,'%'.strval($owner).'%');
      }
      if(strpos($statement, 'WHERE') !== false){ $statement .= ")"; }
    }
    if(strpos($statement, 'WHERE') === false){ $statement .= " WHERE"; }
    if(substr($statement, -1) === '?' || substr($statement, -1) === ')'){ $statement .= ' AND'; }
    $statement .= ' `topic` = ?';
    array_push($values,$topic);
    $notes = $this->select($statement, $values);
    foreach($notes as $key => $note){
      if(isset($note['sharedTo'])){ $note['sharedTo'] = json_decode($note['sharedTo'],true); }
      if(isset($note['linkTo'])){ $note['linkTo'] = json_decode($note['linkTo'],true); }
      if(isset($note['owner'])){ $note['owner'] = json_decode($note['owner'],true); }
      $records[$note['id']] = $note;
    }
    return $records;
  }

  public function getTopic($id, $owners = [], $extend = true) {
    $values = [];
    $statement = "SELECT * FROM topics_topics";
    if(is_array($owners) && count($owners) > 0){
      if(strpos($statement, 'WHERE') === false){ $statement .= " WHERE ("; }
      foreach($owners as $owner){
        if(is_array($owner)){ $owner = json_encode($owner,JSON_UNESCAPED_SLASHES); }
        if(substr($statement, -1) === '?'){ $statement .= ' OR'; }
        $statement .= ' `sharedTo` LIKE ?';
        array_push($values,'%'.strval($owner).'%');
      }
      if(strpos($statement, 'WHERE') !== false){ $statement .= ")"; }
    }
    if(strpos($statement, 'WHERE') === false){ $statement .= " WHERE"; }
    if(substr($statement, -1) === '?' || substr($statement, -1) === ')'){ $statement .= ' AND'; }
    $statement .= ' `id` = ?';
    array_push($values,$id);
    $topics = $this->select($statement, $values);
    foreach($topics as $key => $topic){
      if(isset($topic['meta'])){ $topic['meta'] = json_decode($topic['meta'],true); }
      if(isset($topic['dataset'])){ $topic['dataset'] = json_decode($topic['dataset'],true); }
      if(isset($topic['mids'])){ $topic['mids'] = json_decode($topic['mids'],true); }
      if(isset($topic['emls'])){
        $topic['emls'] = json_decode($topic['emls'],true);
        if($extend){
          $emls = [];
          foreach($topic['emls'] as $id){
            $records = $this->select("SELECT * FROM imap_emls WHERE id = ?", [$id]);
            if(count($records) > 0){
              $eml = $records[0];
              if(isset($eml['bcc'])){ $eml['bcc'] = json_decode($eml['bcc'],true); }
              if(isset($eml['cc'])){ $eml['cc'] = json_decode($eml['cc'],true); }
              if(isset($eml['dataset'])){ $eml['dataset'] = json_decode($eml['dataset'],true); }
              if(isset($eml['files'])){ $eml['files'] = json_decode($eml['files'],true); }
              if(isset($eml['meta'])){ $eml['meta'] = json_decode($eml['meta'],true); }
              if(isset($eml['reference_id'])){ $eml['reference_id'] = json_decode($eml['reference_id'],true); }
              if(isset($eml['sharedTo'])){ $eml['sharedTo'] = json_decode($eml['sharedTo'],true); }
              if(isset($eml['to'])){ $eml['to'] = json_decode($eml['to'],true); }
              if(isset($eml['topics'])){ $eml['topics'] = json_decode($eml['topics'],true); }
              $eml['contacts'] = $eml['to'];
              if(!in_array($eml['account'],$eml['contacts'])){ $eml['contacts'][] = $eml['account']; }
              if(!in_array($eml['from'],$eml['contacts'])){ $eml['contacts'][] = $eml['from']; }
              if(!in_array($eml['sender'],$eml['contacts'])){ $eml['contacts'][] = $eml['sender']; }
              foreach($eml['bcc'] as $contact){
                if(!in_array($contact,$eml['contacts'])){ $eml['contacts'][] = $contact; }
              }
              foreach($eml['cc'] as $contact){
                if(!in_array($contact,$eml['contacts'])){ $eml['contacts'][] = $contact; }
              }
              foreach($eml['to'] as $contact){
                if(!in_array($contact,$eml['contacts'])){ $eml['contacts'][] = $contact; }
              }
              $emls[$id] = $eml;
            }
          }
          $topic['emls'] = $emls;
        }
      }
      if(isset($topic['files'])){
        $topic['files'] = json_decode($topic['files'],true);
        if($extend){
          $files = [];
          foreach($topic['files'] as $id){
            $records = $this->select("SELECT * FROM files WHERE id = ?", [$id]);
            if(count($records) > 0){
              $file = $records[0];
              if(isset($file['content'])){
                unset($file['content']);
              }
              $files[$id] = $file;
            }
          }
          $topic['files'] = $files;
        }
      }
      if(isset($topic['contacts'])){ $topic['contacts'] = json_decode($topic['contacts'],true); }
      if(isset($topic['sharedTo'])){ $topic['sharedTo'] = json_decode($topic['sharedTo'],true); }
      if(!isset($topic['comments'])){ $topic['comments'] = $this->getComments($topic['id']); }
      if(!isset($topic['notes'])){ $topic['notes'] = $this->getNotes($topic['id'], $owners); }
      $topics[$key] = $topic;
    }
    return $topics;
  }

  public function getTopicsList($status = 4, $array = [], $int = null){
    $owners = [];
    $limit = null;
    if(is_int($array) && $array > 0 && $limit == null){ $limit = $array; }
    if(is_int($int) && $int > 0 && $limit == null){ $limit = $int; }
    if(is_array($array) && $owners == null){ $owners = $array; }
    if(is_array($int) && $owners == null){ $owners = $int; }
    $values = [];
    $statement = "SELECT * FROM topics_topics";
    if(is_array($owners) && count($owners) > 0){
      if(strpos($statement, 'WHERE') === false){ $statement .= " WHERE ("; }
      foreach($owners as $owner){
        if(is_array($owner)){ $owner = json_encode($owner,JSON_UNESCAPED_SLASHES); }
        if(substr($statement, -1) === '?'){ $statement .= ' OR'; }
        $statement .= ' `sharedTo` LIKE ?';
        array_push($values,'%'.strval($owner).'%');
      }
      if(strpos($statement, 'WHERE') !== false){ $statement .= ")"; }
    }
    if(strpos($statement, 'WHERE') === false){ $statement .= " WHERE"; }
    if(substr($statement, -1) === '?' || substr($statement, -1) === ')'){ $statement .= ' AND'; }
    $statement .= ' `status` <= ?';
    array_push($values,$status);
    $statement .= ' ORDER BY id DESC';
    if($limit != null){
      if(!is_string($limit) && !is_int($limit)){ $limit = null; }
      if(is_string($limit)){ $limit = intval($limit); }
    }
    if($limit != null){
      $statement .= ' LIMIT ?';
      array_push($values,$limit);
    }
    $topics = $this->select($statement, $values);
    foreach($topics as $key => $topic){
      if(isset($topic['meta'])){ $topic['meta'] = json_decode($topic['meta'],true); }
      if(isset($topic['dataset'])){ $topic['dataset'] = json_decode($topic['dataset'],true); }
      if(isset($topic['emls'])){ $topic['emls'] = json_decode($topic['emls'],true); }
      if(isset($topic['mids'])){ $topic['mids'] = json_decode($topic['mids'],true); }
      if(isset($topic['files'])){ $topic['files'] = json_decode($topic['files'],true); }
      if(isset($topic['contacts'])){ $topic['contacts'] = json_decode($topic['contacts'],true); }
      if(isset($topic['sharedTo'])){ $topic['sharedTo'] = json_decode($topic['sharedTo'],true); }
      $topics[$key] = $topic;
    }
    return $topics;
  }

  public function getTopics($array = [], $int = null){
    $filters = null;
    $limit = null;
    if(is_int($array) && $array > 0 && $limit == null){ $limit = $array; }
    if(is_int($int) && $int > 0 && $limit == null){ $limit = $int; }
    if(is_array($array) && $filters == null){ $filters = $array; }
    if(is_array($int) && $filters == null){ $filters = $int; }
    if(!is_array($filters) || $filters == null){ $filters = []; }
    $values = [];
    $statement = "SELECT * FROM topics_topics";
    $columns = [
      'id',
      'created',
      'modified',
      'status',
      'status>',
      'status<',
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
              array_push($values,'%"'.strval($value).'"%');
              break;
            case "status>":
              $statement .= ' `status` > ?';
              array_push($values,$value);
              break;
            case "status<":
              $statement .= ' `status` < ?';
              array_push($values,$value);
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
    $topics = $this->select($statement, $values);
    foreach($topics as $key => $topic){
      if(isset($topic['meta'])){ $topic['meta'] = json_decode($topic['meta'],true); }
      if(isset($topic['dataset'])){ $topic['dataset'] = json_decode($topic['dataset'],true); }
      if(isset($topic['emls'])){ $topic['emls'] = json_decode($topic['emls'],true); }
      if(isset($topic['mids'])){ $topic['mids'] = json_decode($topic['mids'],true); }
      if(isset($topic['files'])){ $topic['files'] = json_decode($topic['files'],true); }
      if(isset($topic['contacts'])){ $topic['contacts'] = json_decode($topic['contacts'],true); }
      if(isset($topic['sharedTo'])){ $topic['sharedTo'] = json_decode($topic['sharedTo'],true); }
      $topics[$key] = $topic;
    }
    return $topics;
  }

  public function updateTopic($topic = []){
    $values = [];
    $statement = "UPDATE topics_topics SET";
    $columns = [
      'meta',
      'status',
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
            if(substr($statement, -3) !== 'SET'){ $statement .= ','; }
            if(is_array($value)){
              // $topic[$column] = sort($topic[$column]);
              switch($column){
                case "emls":
                case "files":
                  foreach($value as $key => $val){
                    $value[$key] = strval($val);
                  }
                  break;
              }
              $value = json_encode($value,JSON_UNESCAPED_SLASHES);
            }
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
          switch($column){
            case "emls":
            case "files":
              foreach($topic[$column] as $key => $val){
                $topic[$column][$key] = strval($val);
              }
              break;
          }
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
