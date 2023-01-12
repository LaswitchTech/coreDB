<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

class PermissionModel extends BaseModel {

  public function getPermission($id) {
    return $this->select("SELECT * FROM auth_permissions WHERE id = ? ORDER BY id ASC", [$id]);
  }

  public function getPermissions($limit = null) {
    if($limit != null){
      return $this->select("SELECT * FROM auth_permissions ORDER BY id ASC LIMIT ?", [$limit]);
    }
    return $this->select("SELECT * FROM auth_permissions ORDER BY id ASC", []);
  }

  public function addPermission($name) {
    return $this->insert("INSERT INTO auth_permissions (name) VALUES (?)", [$name]);
  }
}
