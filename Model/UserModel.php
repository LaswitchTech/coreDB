<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

class UserModel extends BaseModel {
  public function getUser($id) {
    return $this->select("SELECT * FROM users WHERE id = ? ORDER BY id ASC", [$id]);
  }
  public function getUsers($limit) {
    return $this->select("SELECT * FROM users ORDER BY id ASC LIMIT ?", [$limit]);
  }
}
