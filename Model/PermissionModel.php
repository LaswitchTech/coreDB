<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

class PermissionModel extends BaseModel {
  public function getPermissions() {
    return $this->select("SELECT * FROM permissions ORDER BY id ASC", []);
  }
}
