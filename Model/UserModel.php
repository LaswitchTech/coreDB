<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

class UserModel extends BaseModel {
  public function getUser($userID) {
    return $this->select("SELECT * FROM users WHERE id = ? ORDER BY id ASC", [$userID]);
  }
}
