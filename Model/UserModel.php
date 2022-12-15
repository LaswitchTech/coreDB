<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

class UserModel extends BaseModel {
  public function getUser($id) {
    return $this->select("SELECT * FROM users WHERE username = ? ORDER BY id ASC", [$id]);
  }
  public function getUsers($limit) {
    return $this->select("SELECT * FROM users ORDER BY id ASC LIMIT ?", [$limit]);
  }
  public function deleteUser($id) {
    return $this->delete("DELETE FROM users WHERE username = ?", [$id]);
  }
  public function addUser($username,$password) {
    return $this->insert("INSERT INTO users (username, password) VALUES (?,?)", [$username,password_hash($password, PASSWORD_DEFAULT)]);
  }
}
