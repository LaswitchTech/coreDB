<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

class ImapModel extends BaseModel {

  public function addAccount($account){
    $values = [];
    $fields = '';
    $placeholders = '';
    $columns = ['owner','username','password','host','encryption','port','isSelfSigned'];
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

  public function getAccount($account){
    return $this->select("SELECT * FROM imap_accounts WHERE username = ?", [$account]);
  }

  public function getFetchers(){
    $fetchers = $this->select("SELECT * FROM imap_fetchers WHERE status > ?", [0]);
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
        $files[] = $this->saveFile($file);
      }
      $eml['files'] = $files;
    }
    foreach($eml as $key => $value){
      if(in_array($key,$columns)){
        if(is_array($value)){ $value = json_encode($value,JSON_UNESCAPED_SLASHES); }
        $values[] = $value;
        if($fields != ''){ $fields .= ','; }
        $fields .= '`' . $key . '`';
        if($placeholders != ''){ $placeholders .= ','; }
        $placeholders .= '?';
      }
    }
    return $this->insert("INSERT INTO imap_emls (" . $fields . ") VALUES (" . $placeholders . ")", $values);
  }

  public function saveFile($file){
    $values = [];
    $fields = '';
    $placeholders = '';
    $columns = ['name','filename','content','type','size','encoding','meta'];
    foreach($file as $key => $value){
      if(in_array($key,$columns)){
        if(is_array($value)){ $value = json_encode($value,JSON_UNESCAPED_SLASHES); }
        $values[] = $value;
        if($fields != ''){ $fields .= ','; }
        $fields .= '`' . $key . '`';
        if($placeholders != ''){ $placeholders .= ','; }
        $placeholders .= '?';
      }
    }
    return $this->insert("INSERT INTO imap_files (" . $fields . ") VALUES (" . $placeholders . ")", $values);
  }
}
