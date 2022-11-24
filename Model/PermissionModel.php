<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

class PermissionModel extends BaseModel {
  public function getPermission($id) {
    return $this->select("SELECT * FROM permissions WHERE id = ? ORDER BY id ASC", [$id]);
  }
  public function getPermissions($limit) {
    return $this->select("SELECT * FROM permissions ORDER BY id ASC LIMIT ?", [$limit]);
  }
}
