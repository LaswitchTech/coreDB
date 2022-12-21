<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

//Import phpSMTP class into the global namespace
use LaswitchTech\SMTP\phpSMTP;

class UserModel extends BaseModel {

  protected $SMTP = null;

  public function __construct(){

    // Load Parent Constructor
    $return = parent::__construct();

    // Setup phpSMTP
    $this->SMTP = new phpSMTP();

    // Return
    return $return;
  }

  protected function hex($length = 16){
    return bin2hex(openssl_random_pseudo_bytes($length));
  }

  public function getUser($username) {
    return $this->select("SELECT * FROM users WHERE username = ? ORDER BY id ASC", [$username]);
  }

  public function getUsers($limit) {
    return $this->select("SELECT * FROM users ORDER BY id ASC LIMIT ?", [$limit]);
  }

  public function deleteUser($username) {
    return $this->delete("DELETE FROM users WHERE username = ?", [$username]);
  }

  public function saveUser($user) {
    if(isset($user['username'])){
      $values = [];
      $statement = '';
      foreach($user as $key => $value){
        if($statement != ''){ $statement .= ', '; }
        $statement .= $key . ' = ?';
        array_push($values, $value);
      }
      array_push($values, $user['username']);
      $affected = $this->update("UPDATE users SET " . $statement . " WHERE username = ?", $values);
      if($affected){
        return $affected;
      }
    }
    return false;
  }

  public function addUser($username,$password = null) {
    if($password == null){ $password = $this->hex(6); }
    $id = $this->insert("INSERT INTO users (username, password) VALUES (?,?)", [$username,password_hash($password, PASSWORD_DEFAULT)]);
    if($id){
      $message = '<p>Dear ' . $username . ',<br>';
      $message .= 'Your account was successfully created.</p>';
      $message .= '<p>Here is the password of your new account: <strong style="background-color: #CCC;padding-left:8px;padding-right:8px;">' . $password . '</strong></p>';
      if($this->SMTP->send([
        "TO" => $username,
        "SUBJECT" => "Your new account was created",
        "TITLE" => "New Account",
        "MESSAGE" => $message,
      ])){
        return $id;
      }
    }
    return false;
  }

  public function addAPI($username, $email = null) {
    $token = $this->hex();
    $id = $this->insert("INSERT INTO users (username, token, status, isActive, isAPI) VALUES (?,?,?,?,?)", [$username,$token,3,1,1]);
    if($id){
      if($email == null){ $email = $username; }
      $message = '<p>Dear ' . $username . ',<br>';
      $message .= 'Your API account is ready.</p>';
      $message .= '<p>Here is your API token: <strong style="background-color: #CCC;padding-left:8px;padding-right:8px;">' . $token . '</strong></p>';
      if($this->SMTP->send([
        "TO" => $email,
        "SUBJECT" => "Your API account is ready",
        "TITLE" => "API Account",
        "MESSAGE" => $message,
      ])){
        return $id;
      }
    }
    return false;
  }

  public function activateUser($username,$token = null){
    if($token != null){
      $affected = $this->update("UPDATE users SET status = ?, isActive = ?, token = ? WHERE username = ? AND token = ?", [3, 1, null, $username, base64_decode($token)]);
    } else {
      $affected = $this->update("UPDATE users SET status = ?, isActive = ? WHERE username = ?", [3, 1, $username]);
    }
    if($affected){
      $message = '<p>Dear ' . $username . ',<br>';
      $message .= 'Your account was successfully activated.</p>';
      if($this->SMTP->send([
        "TO" => $username,
        "SUBJECT" => "Your account was activated",
        "TITLE" => "Activation Successfull",
        "MESSAGE" => $message,
      ])){
        return $token;
      }
    }
    return false;
  }

  public function deactivateUser($username){
    $token = $this->hex();
    $affected = $this->update("UPDATE users SET status = ?, isActive = ?, token = ? WHERE username = ?", [0,0, $token, $username]);
    if($affected){
      $message = '<p>Dear ' . $username . ',<br>';
      $message .= 'To activate your account, click the link below and sign in with your new account.</p>';
      $message .= '<p><a href="' . ROOT_URL . '?token=' . base64_encode($token) . '" style="text-decoration: none;">Click here to activate your account</a></p>';
      if($this->SMTP->send([
        "TO" => $username,
        "SUBJECT" => "Activate your account",
        "TITLE" => "Account Activation",
        "MESSAGE" => $message,
      ])){
        return $token;
      }
    }
    return false;
  }

  public function disableUser($username){
    return $this->update("UPDATE users SET status = ?, isActive = ? WHERE username = ?", [1,0, $username]);
  }

  public function convertToAPI($username){
    return $this->update("UPDATE users SET isAPI = ? WHERE username = ?", [1, $username]);
  }

  public function convertToUser($username){
    return $this->update("UPDATE users SET isAPI = ? WHERE username = ?", [0, $username]);
  }
}
