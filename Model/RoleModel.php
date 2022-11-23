<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

class RoleModel extends BaseModel {
  public function getRoles() {
    return $this->select("SELECT * FROM roles ORDER BY id ASC", []);
  }
}
