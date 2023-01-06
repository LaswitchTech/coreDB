<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

//Import phpSMTP class into the global namespace
use LaswitchTech\SMTP\phpSMTP;

//Import Configurator class into the global namespace
use LaswitchTech\coreDB\Configurator;

class UserModel extends BaseModel {

  protected $SMTP = null;
  protected $Configurator = null;
  protected $Activity = null;

  public function __construct(){

    // Setup Configurator
    $this->Configurator = new Configurator();

    // Load Parent Constructor
    $return = parent::__construct();

    // Setup phpSMTP
    $this->SMTP = new phpSMTP();

    // Load Activities
    if(is_file(dirname(__FILE__) . '/ActivityModel.php')){
      require_once dirname(__FILE__) . '/ActivityModel.php';
      $this->Activity = new ActivityModel();
    }

    // Return
    return $return;
  }

  protected function hex($length = 16){
    return bin2hex(openssl_random_pseudo_bytes($length));
  }

  public function getUser($username) {
    return $this->select("SELECT * FROM auth_users WHERE username = ? ORDER BY id ASC", [$username]);
  }

  public function getUsers($limit) {
    return $this->select("SELECT * FROM auth_users ORDER BY id ASC LIMIT ?", [$limit]);
  }

  public function deleteUser($username) {
    return $this->delete("DELETE FROM auth_users WHERE username = ?", [$username]);
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
      $affected = $this->update("UPDATE auth_users SET " . $statement . " WHERE username = ?", $values);
      if($affected){
        return $affected;
      }
    }
    return false;
  }

  public function addUser($username,$password = null) {
    if($password == null){ $password = $this->hex(6); }
    $id = $this->insert("INSERT INTO auth_users (username, password) VALUES (?,?)", [$username,password_hash($password, PASSWORD_DEFAULT)]);
    if($id){
      $roles = $this->select("SELECT * FROM auth_roles WHERE isDefault = ?", [1]);
      $defaults = [];
      foreach($roles as $role){
        array_push($defaults,["roles" => $role['id']]);
        $role['members'] = json_decode($role['members'],true);
        array_push($role['members'],["users" => $id]);
        $this->update("UPDATE auth_roles SET members = ? WHERE name = ?", [json_encode($role['members'],JSON_UNESCAPED_SLASHES),$role['name']]);
      }
      $this->update("UPDATE auth_users SET roles = ? WHERE id = ?", [json_encode($defaults,JSON_UNESCAPED_SLASHES),$id]);
      $activity = $this->Activity->addActivity(['users' => $id],[
        "header" => "Welcome",
        "sharedTo" => [['users' => $id]],
        "icon" => 'balloon',
        "color" => 'info',
      ]);
      if($activity){
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
    }
    return false;
  }

  public function addAPI($username, $email = null) {
    $token = $this->hex();
    $id = $this->insert("INSERT INTO auth_users (username, token, status, isActive, isAPI) VALUES (?,?,?,?,?)", [$username,$token,3,1,1]);
    if($id){
      if($email == null){ $email = $username; }
      $activity = $this->Activity->addActivity(['users' => $id],[
        "header" => "Welcome " . $username,
        "sharedTo" => [['users' => $id]],
        "icon" => 'balloon',
        "color" => 'info',
      ]);
      if($activity){
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
    }
    return false;
  }

  public function activateUser($username,$token = null){
    if($token != null){
      $affected = $this->update("UPDATE auth_users SET status = ?, isActive = ?, token = ? WHERE username = ? AND token = ?", [3, 1, null, $username, base64_decode($token)]);
    } else {
      $affected = $this->update("UPDATE auth_users SET status = ?, isActive = ? WHERE username = ?", [3, 1, $username]);
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

  public function recoverUser($username){
    $token = $this->hex();
    $affected = $this->update("UPDATE auth_users SET token = ? WHERE username = ?", [$token, $username]);
    if($affected){
      $message = '<p>Dear ' . $username . ',<br>';
      $message .= 'You are attempting to recover your account. If you did not request this, please discard this message.</p>';
      $message .= '<p><a href="' . ROOT_URL . 'recover?token=' . base64_encode($token) . '" style="text-decoration: none;">Click here to recover your account</a></p>';
      if($this->SMTP->send([
        "TO" => $username,
        "SUBJECT" => "Recover your account",
        "TITLE" => "Account Recovery",
        "MESSAGE" => $message,
      ])){
        return $token;
      }
    }
    return false;
  }

  public function recoveredUser($username,$token,$password){
    $affected = $this->update("UPDATE auth_users SET password = ? WHERE username = ? AND token = ?", [password_hash($password, PASSWORD_DEFAULT), $username, $token]);
    if($affected){
      $message = '<p>Dear ' . $username . ',<br>';
      $message .= 'Your account was successfully recovered.</p>';
      $message .= '<p>Here is the password of your new account: <strong style="background-color: #CCC;padding-left:8px;padding-right:8px;">' . $password . '</strong></p>';
      if($this->SMTP->send([
        "TO" => $username,
        "SUBJECT" => "Account successfully recovered",
        "TITLE" => "Account Recovered",
        "MESSAGE" => $message,
      ])){
        return $token;
      }
    }
    return false;
  }

  public function deactivateUser($username){
    $token = $this->hex();
    $affected = $this->update("UPDATE auth_users SET status = ?, isActive = ?, token = ? WHERE username = ?", [0,0, $token, $username]);
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
    $affected = $this->update("UPDATE auth_users SET status = ?, isActive = ? WHERE username = ?", [1,0, $username]);
    if($affected){
      $message = '<p>Dear ' . $username . ',<br>';
      $message .= 'Your account was disabled by an administrator. Please contact the support team.</p>';
      if($this->SMTP->send([
        "TO" => $username,
        "SUBJECT" => "Your account was disabled",
        "TITLE" => "Account Disabled",
        "MESSAGE" => $message,
      ])){
        return $affected;
      }
    }
    return false;
  }

  public function suspendUser($username){
    $affected = $this->update("UPDATE auth_users SET status = ?, isActive = ? WHERE username = ?", [2,0, $username]);
    if($affected){
      $message = '<p>Dear ' . $username . ',<br>';
      $message .= 'Your account was suspended for security reasons. We detected some unusual activity with your account. Please contact the support team.</p>';
      if($this->SMTP->send([
        "TO" => $username,
        "SUBJECT" => "Your account was suspended",
        "TITLE" => "Account Suspended",
        "MESSAGE" => $message,
      ])){
        return $affected;
      }
    }
    return false;
  }

  public function convertToAPI($username){
    return $this->update("UPDATE auth_users SET isAPI = ? WHERE username = ?", [1, $username]);
  }

  public function convertToUser($username){
    return $this->update("UPDATE auth_users SET isAPI = ? WHERE username = ?", [0, $username]);
  }
}
