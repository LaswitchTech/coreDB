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
      if(isset($file['path'])){
        $file['content'] = file_get_contents(dirname(__FILE__,2) . $file['path'] . $file['id'] . '.bin');
      } else {
        $file['content'] = null;
      }
      return $file;
    }
  }

  public function deleteDir($dirPath, $isRelative = false) {
    if($isRelative){
      if(!str_starts_with($dirPath, '/')){ $dirPath = '/' . $dirPath; }
      $dirPath = dirname(__FILE__,2) . $dirPath;
    }
    if(!is_dir($dirPath)){
      throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if(substr($dirPath, strlen($dirPath) - 1, 1) != '/'){
      $dirPath .= '/';
    }
    $files = glob($dirPath . '{,.}*[!.]*', GLOB_MARK | GLOB_BRACE);
    foreach($files as $file){
      if(is_dir($file)){
        $this->deleteDir($file);
      } else {
        unlink($file);
      }
    }
    rmdir($dirPath);
  }

  public function deleteFile($id, $permanentaly = false){
    if($permanentaly){
      return $this->delete("DELETE FROM files WHERE id = ?", [$id]);
    } else {
      return $this->update("UPDATE files SET isDeleted = ? WHERE id = ?", [1, $id]);
    }
  }

  public function restoreFile($id){
    return $this->update("UPDATE files SET isDeleted = ? WHERE id = ?", [0, $id]);
  }

  public function publishFile($id){
    return $this->update("UPDATE files SET isPublic = ? WHERE id = ?", [1, $id]);
  }

  public function unpublishFile($id){
    return $this->update("UPDATE files SET isPublic = ? WHERE id = ?", [0, $id]);
  }

  public function saveFile($file){
    $values = [];
    $fields = '';
    $placeholders = '';
    $columns = ['name','filename','path','created','type','size','meta','dataset','checksum','sharedTo','isPublic','isDeleted'];
    if(isset($file['path'])){
      if(!str_starts_with($file['path'], '/') && !str_ends_with($this->Configurator->getDataDir(), '/')){
        $file['path'] = '/' . $file['path'];
      } else {
        if(str_starts_with($file['path'], '/') && str_ends_with($this->Configurator->getDataDir(), '/')){
          $file['path'] = ltrim($file['path'], '/');
        }
      }
      $file['path'] = '/' . $this->Configurator->getDataDir() . $file['path'];
      if(!str_ends_with($file['path'], '/')){
        $file['path'] = $file['path'] . '/';
      }
      if(str_starts_with($file['path'], '.')){
        $file['path'] = ltrim($file['path'], '.');
      }
    }
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
      if(isset($file['content'])){
        $this->Configurator->mkdir($file['path']);
        file_put_contents(dirname(__FILE__,2) . $file['path'] . $id . '.bin', $file['content']);
      }
    }
    return $id;
  }

  public function uploadFile($file){
    $id = $this->saveFile($file);
    if($id){
      $files = $this->select("SELECT * FROM files WHERE id = ?", [$id]);
      if(count($files) > 0){
        return $files[0];
      }
    }
  }
}
