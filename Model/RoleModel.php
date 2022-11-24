<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

class RoleModel extends BaseModel {
  public function getRole($id) {
    return $this->select("SELECT * FROM roles WHERE id = ? ORDER BY id ASC", [$id]);
  }
  public function getRoles($limit) {
    return $this->select("SELECT * FROM roles ORDER BY id ASC LIMIT ?", [$limit]);
  }
}
