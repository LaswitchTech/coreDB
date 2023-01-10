<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

//Import Configurator class into the global namespace
use LaswitchTech\coreDB\Configurator;

class ImapModel extends BaseModel {

  protected $Configurator = null;

  public function __construct(){

    // Setup Configurator
    $this->Configurator = new Configurator();

    // Load Parent Constructor
    $return = parent::__construct();

    // Return
    return $return;
  }

  public function addAccount($account){
    $values = [];
    $fields = '';
    $placeholders = '';
    $columns = ['owner','username','password','host','encryption','port','isSelfSigned'];
    if(isset($account['password'])){
      if(isset($account['username'])){ $publicKey = $account['username']; }
      if(isset($account['key'])){ $publicKey = $account['key']; }
      $this->Configurator->Encryption->setPublicKey($publicKey);
      $account['password'] = $this->Configurator->Encryption->encrypt($account['password']);
    }
    foreach($account as $key => $value){
      if(in_array($key,$columns)){
        if(is_array($value)){ $value = json_encode($value,JSON_UNESCAPED_SLASHES); }
        $values[] = $value;
        if($fields != ''){ $fields .= ','; }
        $fields .= $key;
        if($placeholders != ''){ $placeholders .= ','; }
        $placeholders .= '?';
      }
    }
    return $this->insert("INSERT INTO imap_accounts (" . $fields . ") VALUES (" . $placeholders . ")", $values);
  }

  public function addFetcher($account, $folder = 'INBOX'){
    $accounts = $this->select("SELECT * FROM imap_accounts WHERE username = ?", [$account]);
    if(count($accounts) > 0){
      return $this->insert("INSERT INTO imap_fetchers (account,folder) VALUES (?,?)", [$account,$folder]);
    }
    return false;
  }

  public function enable($account){
    return $this->update("UPDATE imap_fetchers SET status = ? WHERE account = ?", [1,$account]);
  }

  public function disable($account){
    return $this->update("UPDATE imap_fetchers SET status = ? WHERE account = ?", [0,$account]);
  }

  public function getAccount($username){
    $accounts = $this->select("SELECT * FROM imap_accounts WHERE username = ?", [$username]);
    if(count($accounts) > 0){
      foreach($accounts as $key => $account){
        if(isset($account['password'])){
          if(isset($account['username'])){ $publicKey = $account['username']; }
          if(isset($account['key'])){ $publicKey = $account['key']; }
          $this->Configurator->Encryption->setPublicKey($publicKey);
          $account['password'] = $this->Configurator->Encryption->decrypt($account['password']);
          $accounts[$key] = $account;
        }
      }
    }
    return $accounts;
  }

  public function getEmls($filters = [], $limit = null){
    $values = [];
    $statement = "SELECT * FROM imap_emls";
    $columns = [
      'id',
      'created',
      'modified',
      'account',
      'folder',
      'date',
      'mid',
      'uid',
      'reply_to_id',
      'reference_id',
      'sender',
      'from',
      'to',
      'cc',
      'bcc',
      'meta',
      'subject',
      'subject_stripped',
      'body',
      'body_stripped',
      'files',
      'topics',
      'sharedTo',
      'isLinked',
      'isRead',
    ];
    if(is_array($filters) && count($filters) > 0){
      foreach($filters as $column => $value){
        if(in_array($column,$columns)){
          if(is_array($value)){ $value = json_encode($value,JSON_UNESCAPED_SLASHES); }
          if(strpos($statement, 'WHERE') === false){ $statement .= " WHERE"; }
          if(substr($statement, -1) === '?'){ $statement .= ' AND'; }
          switch($column){
            case "reference_id":
            case "to":
            case "cc":
            case "bcc":
            case "meta":
            case "files":
            case "sharedTo":
              $statement .= ' `' . $column . '` LIKE ?';
              array_push($values,'%"'.$value.'"%');
              break;
            case "topics":
            case "files":
              $statement .= ' `' . $column . '` LIKE ?';
              array_push($values,'%'.$value.'%');
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
      if(isset($eml['to'])){ $eml['to'] = json_decode($eml['to'],true); }
      if(isset($eml['cc'])){ $eml['cc'] = json_decode($eml['cc'],true); }
      if(isset($eml['bcc'])){ $eml['bcc'] = json_decode($eml['bcc'],true); }
      if(isset($eml['meta'])){ $eml['meta'] = json_decode($eml['meta'],true); }
      if(isset($eml['dataset'])){ $eml['dataset'] = json_decode($eml['dataset'],true); }
      if(isset($eml['files'])){ $eml['files'] = json_decode($eml['files'],true); }
      if(isset($eml['topics'])){ $eml['topics'] = json_decode($eml['topics'],true); }
      if(isset($eml['sharedTo'])){ $eml['sharedTo'] = json_decode($eml['sharedTo'],true); }
      if(isset($eml['reference_id'])){ $eml['reference_id'] = json_decode($eml['reference_id'],true); }
      $emls[$key] = $eml;
    }
    return $emls;
  }

  public function updateEml($eml = []){
    $values = [];
    $statement = "UPDATE imap_emls SET";
    $columns = [
      'account',
      'folder',
      'date',
      'mid',
      'uid',
      'reply_to_id',
      'reference_id',
      'sender',
      'from',
      'to',
      'cc',
      'bcc',
      'meta',
      'dataset',
      'subject',
      'subject_stripped',
      'body',
      'body_stripped',
      'files',
      'topics',
      'sharedTo',
      'isLinked',
      'isRead',
    ];
    if(is_array($eml) && count($eml) > 0){
      if(isset($eml['id']) || isset($eml['mid'])){
        foreach($eml as $column => $value){
          if(in_array($column,$columns)){
            if(is_array($value)){ $value = json_encode($value,JSON_UNESCAPED_SLASHES); }
            if(substr($statement, -3) !== 'SET'){ $statement .= ','; }
            $statement .= ' `' . $column . '` = ?';
            array_push($values,$value);
          }
        }
        if(isset($eml['id'])){
          $statement .= ' WHERE id = ?';
          array_push($values,$eml['id']);
        } else if(isset($eml['mid'])){
          $statement .= ' WHERE mid = ?';
          array_push($values,$eml['mid']);
        }
        return $this->update($statement, $values);
      }
    }
    return null;
  }

  public function getFetchers($status = 1){
    $fetchers = $this->select("SELECT * FROM imap_fetchers WHERE status >= ?", [$status]);
    // foreach($fetchers as $key => $fetcher){}
    return $fetchers;
  }

  public function saveEml($eml){
    $values = [];
    $fields = '';
    $placeholders = '';
    $columns = ['account','folder','date','mid','uid','reply_to_id','reference_id','sender','from','to','cc','bcc','meta','subject','subject_stripped','body','body_stripped','files'];
    if(isset($eml['files']) && is_array($eml['files']) && count($eml['files']) > 0){
      $files = [];
      foreach($eml['files'] as $key => $file){
        $fileID = $this->saveFile($file);
        if($fileID){
          $files[] = $fileID;
        }
      }
      $eml['files'] = $files;
    }
    if(isset($eml['reference_id'])){ $eml['reference_id'] = explode(' ',$eml['reference_id']); }
    if(!isset($eml['dataset']) || $eml['dataset'] == null){ $eml['dataset'] = []; }
    if(!isset($eml['sharedTo']) || $eml['sharedTo'] == null){ $eml['sharedTo'] = []; }
    foreach($eml as $key => $value){
      if(in_array($key,$columns)){
        if(is_string($value)){ $value = trim($value); }
        if(is_array($value)){ $value = json_encode($value,JSON_UNESCAPED_SLASHES); }
        $values[] = $value;
        if($fields != ''){ $fields .= ','; }
        $fields .= '`' . $key . '`';
        if($placeholders != ''){ $placeholders .= ','; }
        $placeholders .= '?';
      }
    }
    $id = $this->insert("INSERT INTO imap_emls (" . $fields . ") VALUES (" . $placeholders . ")", $values);
    return $id;
  }

  public function saveFile($file){
    $values = [];
    $fields = '';
    $placeholders = '';
    $columns = ['name','filename','content','type','size','encoding','meta','checksum'];
    if(isset($file['content'])){
      $file['checksum'] = sha1($file['content']);
    }
    foreach($file as $key => $value){
      if(in_array($key,$columns)){
        if(is_string($value)){ $value = trim($value); }
        if(is_array($value)){ $value = json_encode($value,JSON_UNESCAPED_SLASHES); }
        $values[] = $value;
        if($fields != ''){ $fields .= ','; }
        $fields .= '`' . $key . '`';
        if($placeholders != ''){ $placeholders .= ','; }
        $placeholders .= '?';
      }
    }
    $id = $this->insert("INSERT INTO imap_files (" . $fields . ") VALUES (" . $placeholders . ")", $values);
    if($id < 1 || $id == null){
      $id = $this->select("SELECT * FROM imap_files WHERE checksum = ?", [$file['checksum']]);
      if(count($id) > 0){
        $id = $id[0]['id'];
      } else {
        $id = 0;
      }
    }
    return $id;
  }
}
