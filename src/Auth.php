<?php

//Declaring namespace
namespace LaswitchTech\coreDB;

//Import phpAUTH class into the global namespace
use LaswitchTech\phpAUTH\phpAUTH;

//Import phpSMTP class into the global namespace
use LaswitchTech\SMTP\phpSMTP;

class Auth extends phpAUTH {

  protected $SMTP = null;
  protected $Configurator = null;

  public function __construct($fronttype = null, $backtype = null, $roles = null, $groups = null, $output = null, $return = null){

    // Configure Auth
    $this->Configurator = new Configurator();

    // Initiate Parent Constructor
    parent::__construct($fronttype = null, $backtype = null, $roles = null, $groups = null, $output = null, $return = null);

    // Setup phpSMTP
    $this->SMTP = new phpSMTP();

    // Activate User
    if($this->getUser('isActive') == 1){
      switch($this->getUser('status')){
        case 0: $this->activateUser($this->getUser('username'));break;
      }
    }
  }

  protected function activateUser($username){
    $affected = $this->Database->update("UPDATE users SET status = ?, isActive = ? WHERE username = ?", [3, 1, $username]);
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
}
