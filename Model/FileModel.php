<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

//Import Configurator class into the global namespace
use LaswitchTech\coreDB\Configurator;

class FileModel extends BaseModel {

  protected $Configurator = null;

  public function __construct(){

    // Setup Configurator
    $this->Configurator = new Configurator();

    // Load Parent Constructor
    $return = parent::__construct();

    // Return
    return $return;
  }

  public function getFile($id){
    $files = $this->select("SELECT * FROM files WHERE id = ?", [$id]);
    if(count($files) > 0){
      $file = $files[0];
      if(isset($file['sharedTo'])){
        if($file['sharedTo'] != null){
          $file['sharedTo'] = json_decode($file['sharedTo'],true);
        } else {
          $file['sharedTo'] = [];
        }
      } else {
        $file['sharedTo'] = [];
      }
      if(isset($file['meta'])){
        if($file['meta'] != null){
          $file['meta'] = json_decode($file['meta'],true);
        } else {
          $file['meta'] = [];
        }
      } else {
        $file['meta'] = [];
      }
      if(isset($file['dataset'])){
        if($file['dataset'] != null){
          $file['dataset'] = json_decode($file['dataset'],true);
        } else {
          $file['dataset'] = [];
        }
      } else {
        $file['dataset'] = [];
      }
      return $file;
    }
  }

  public function saveFile($file){
    $values = [];
    $fields = '';
    $placeholders = '';
    $columns = ['name','filename','content','type','size','encoding','meta','dataset','checksum','sharedTo'];
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
    $lookup = $this->select("SELECT * FROM files WHERE checksum = ?", [$file['checksum']]);
    if(is_array($lookup) && count($lookup) > 0){
      $id = $lookup[0]['id'];
    } else {
      $id = $this->insert("INSERT INTO files (" . $fields . ") VALUES (" . $placeholders . ")", $values);
    }
    return $id;
  }
}
