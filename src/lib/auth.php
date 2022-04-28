<?php

// Import Librairies
require_once dirname(__FILE__,3) . '/src/lib/imap.php';
require_once dirname(__FILE__,3) . '/src/lib/smtp.php';
require_once dirname(__FILE__,3) . '/src/lib/sql.php';

class Auth{

  public $SMTP;
  public $IMAP;
  public $SQL;
  protected $Settings = [];
  protected $Manifest = [];
  protected $Fields = [];
  public $Username = null;
  public $sessionID = null;
  public $Debug = false;
  public $User;
  public $Groups = [];
  public $Roles = [];
  public $Permissions = [];
  public $Options = [];
  protected $Log = "tmp/access.log";
  protected $Logger = false;

  public function __construct($settings = [],$manifest = [],$fields = []){
    if(!empty($settings)){ $this->Settings = $settings; }
    if(!empty($manifest)){ $this->Manifest = $manifest; }
    if(!empty($fields)){ $this->Fields = $fields; }
    // Setup Logger
    $this->Log = dirname(__FILE__,3) . "/tmp/access.log";
		if(isset($this->Settings['log']['access']['status'])){ $this->Logger = $this->Settings['log']['access']['status']; }
    if(isset($this->Settings['log']['access']['location'])){ $this->Log = $this->Settings['log']['access']['location']; }
		// Setup Debug
		if(isset($this->Settings['debug']) && $this->Settings['debug']){ $this->Debug = true; }
    // Connect to IMAP
    if(isset($this->Settings['imap'])){
      $this->IMAP = new IMAP($this->Settings['imap']['host'],$this->Settings['imap']['port'],$this->Settings['imap']['encryption'],$this->Settings['imap']['username'],$this->Settings['imap']['password']);
    } else { $this->IMAP = new IMAP(); }
    // Connect to SMTP
    if(isset($this->Settings['smtp'])){
      $this->SMTP = new MAILER($this->Settings['smtp'],$this->Fields);
      // Customize the SMTP Mailer
      $links = [
        "logo" => $this->Settings['url']."dist/img/logo.png",
        "support" => str_replace('.git','',$this->Manifest['repository']),
      ];
      $this->SMTP->customization($this->Settings['name'],$links);
    } else { $this->SMTP = new MAILER(); }
    if(isset($this->Settings['sql'])){
      $this->SQL = new SQL($this->Settings['sql'],$this->Fields);
    } else { $this->SQL = new SQL(); }
    $this->sessionID = session_id();
    $this->authenticate();
    $this->setSession();
    $this->protectUser();
  }

  protected function getField($field){
    if(isset($this->Fields[$field])){ return $this->Fields[$field]; }
    else { return 'Unknown field ['.$field.'] in '.$this->Language; }
  }

  protected function setSession(){
    if($this->sessionID != null && $this->isLogin()){
      $type = "update";
      $conditions = ['conditions' => ['sessionID' => '=']];
      $statement = $this->SQL->database->prepare('select','sessions', ['conditions' => ['sessionID' => '=']]);
      $sessions = $this->SQL->database->query($statement,$this->sessionID)->fetchAll();
      if(count($sessions) > 0){
        $session = $sessions[0];
        unset($session['id']);
        $session['userActivity'] = date("Y-m-d H:i:s");
        $values = array_values($session);
        array_push($values,$session['sessionID']);
        $statement = $this->SQL->database->prepare('update','sessions',$session,['conditions' => ['sessionID' => '=']]);
        $this->SQL->database->query($statement,$values);
      }
      if(!isset($session) || empty($session)){
        $session['sessionID'] = $this->sessionID;
        $session['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
        $session['userBrowser'] = $this->getClientBrowser();
        $session['userIP'] = $this->getClientIP();
        $session['userData'] = json_encode($this->User);
        $type = "insert";
        $conditions = [];
        $statement = $this->SQL->database->prepare('insert','sessions',$session,$conditions);
        $this->SQL->database->query($statement,$session);
      }
      $statement = $this->SQL->database->prepare('select','sessions', ['conditions' => ['sessionID' => '=']]);
      $session = $this->SQL->database->query($statement,$this->sessionID)->fetchAll()[0];
      $this->User['sessionID'] = $session['sessionID'];
      $this->User['userActivity'] = $session['userActivity'];
    }
  }

  protected function setCookie($username){
    $expiry = time() + (86400 * 30); // 86400 = 1 day
    if(isset($_COOKIE['sessionID'])){
      $cookie = json_decode( $_COOKIE[ "sessionID" ] );
      $expiry = $cookie->expiry;
    }
    $cookieData = (object) array( "sessionID" => $this->sessionID, "username" => $username, "expiry" => $expiry );
    setcookie( "sessionID", json_encode( $cookieData ), $expiry );
  }

  protected function protectUser(){
    unset($this->User['password']);
    unset($this->User['keyActivation']);
    unset($this->User['key2FA']);
    unset($this->User['attempts']);
  }

  protected function setUser($user){
    $this->User = $user['user'];
    $this->Groups = $user['groups'];
    $this->Roles = $user['roles'];
    $this->Permissions = $user['permissions'];
    $this->Options = $user['options'];
  }

  protected function authenticate(){
    if($this->isLogin()){
      $this->Username = $_SESSION[$this->sessionID];
      $this->setUser($this->getUser($this->Username));
      $this->log("[".$this->Username."] is already connected");
    } elseif(isset($_COOKIE['sessionID'])){
      $cookie = json_decode( $_COOKIE[ "sessionID" ] );
      $this->Username = $cookie->username;
      $_SESSION[$this->sessionID] = $this->Username;
      $statement = $this->SQL->database->prepare('select','sessions', ['conditions' => ['sessionID' => '=']]);
      $sessions = $this->SQL->database->query($statement,$cookie->sessionID)->fetchAll();
      if(count($sessions) > 0){
        $statement = $this->SQL->database->prepare('update','sessions',['sessionID'],['conditions' => ['id' => '=']]);
        $this->SQL->database->query($statement,[$this->sessionID,$sessions[0]['id']]);
      }
      $this->setUser($this->getUser($this->Username));
      $this->setCookie($cookie->username);
      $this->log("[".$this->Username."] is connected using cookies");
    } else {
      if(isset($_POST['signin'],$_POST['username'],$_POST['password'])){
        if($this->login($_POST['username'],$_POST['password'])){
          $_SESSION[$this->sessionID] = $this->User['username'];
          $this->Username = $this->User['username'];
          if(isset($_POST['remember'])){ $this->setCookie($this->Username); }
          $this->log("[".$this->Username."] is now connected");
        }
      } elseif(isset($_POST['forgot'],$_POST['username'])){
        if($user = $this->getUser($_POST['username'])){
          if($user['user']['keyActivation'] == null && $user['user']['type'] == 'sql'){
            if($query = $this->SQL->database->prepare('update','users', ['keyActivation'])){
              $keyActivation = $this->genKey();
              $hash = password_hash($keyActivation, PASSWORD_BCRYPT, array("cost" => 10));
              if($this->SQL->database->query($query,['keyActivation' => $hash, 'id' => $user['user']['id']])){
                $this->log("[".$user['user']['username']."] is attempting to reset his password");
                $body = 'Dear '.$user['user']['name'].',<br>';
                $body .= 'You are attempting to reset the password on your account. If you did not request this, simply discard this message.<br><br>';
                $body .= 'To change your password, click the link below.<br><br>';
                $body .= '<a href="'.$this->Settings['url'].'?forgot='.$keyActivation.'" style="color:#0088cc" class="arrow-right">Click here to reset your password</a>';
                $extra = [
                  'title' => 'Forgot your Password',
                  'subject' => 'Forgot your Password',
                ];
                if($this->SMTP->send($user['user']['username'],$body,$extra)){
                  $this->log("[".$user['user']['username']."] has been notified");
                  $this->SQL->database->createRelationship(['users' => $user['user']['id']],null,['alert' => ['name' => 'Forgot your Password', 'text' => 'User is attempting to reset his password', 'icon' => 'fa-solid fa-question', 'color' => 'info']]);
                } else { $this->log("Unable to send notification"); }
              } else { $this->log("[".$user['user']['username']."] Could not setup password reset"); }
            } else { $this->log("[".$user['user']['username']."] Could not setup password reset"); }
          }
        }
      } elseif(isset($_POST['reset'],$_POST['username'],$_POST['keyActivation'],$_POST['password'],$_POST['confirm'])){
        if($user = $this->getUser($_POST['username'])){
          if($user['type'] == 'sql'){
            if(password_verify($_POST['keyActivation'],$user['user']['keyActivation']) && $_POST['password'] == $_POST['confirm'] && !password_verify($_POST['password'],$user['user']['password'])){
              $uppercase = preg_match('@[A-Z]@', $_POST['password']);
              $lowercase = preg_match('@[a-z]@', $_POST['password']);
              $number    = preg_match('@[0-9]@', $_POST['password']);
              $specialChars = preg_match('@[^\w]@', $_POST['password']);
              if($uppercase && $lowercase && $number && $specialChars || strlen($_POST['password']) >= 8){
                $hash = password_hash($_POST['password'], PASSWORD_BCRYPT, array("cost" => 10));
                if($query = $this->SQL->database->prepare('update','users', ['password','keyActivation'])){
                  if($this->SQL->database->query($query,['password' => $hash,'keyActivation' => null, 'id' => $user['user']['id']])){
                    $this->log("[".$user['user']['username']."] has reset his password");
                    $body = 'Dear '.$user['user']['name'].',<br>';
                    $body .= 'Your password was successfully changed.<br><br>';
                    $extra = [
                      'title' => 'Password Reset',
                      'subject' => 'Password Reset',
                    ];
                    if($this->SMTP->send($user['user']['username'],$body,$extra)){
                      $this->log("[".$user['user']['username']."] has been notified");
                      $this->SQL->database->createRelationship(['users' => $user['user']['id']],null,['alert' => ['name' => 'Password Reset', 'text' => 'User has reset his password', 'icon' => 'fa-solid fa-check', 'color' => 'success']]);
                    } else { $this->log("Unable to send notification"); }
                  } else { $this->log("[".$user['user']['username']."] Could not reset password"); }
                } else { $this->log("[".$user['user']['username']."] Could not reset password"); }
              }
            }
          }
        }
      }
    }
    if(!$this->isActivated() || $this->isDeactivated()){
      if(isset($_POST['activate'],$_POST['keyActivation'])){ $this->activate($_POST['keyActivation']); }
    }
  }

  public function getUser($username){
    $lookup = ["username" => "="];
    $username = strtoupper($username);
    if(is_numeric($username)){ $username = intval($username); }
    if(is_int($username)){ $lookup = ["id" => "="]; }
    $statement = $this->SQL->database->prepare('select','users', ['conditions' => $lookup]);
    $users = $this->SQL->database->query($statement,$username)->fetchAll();
    if(count($users) > 0){
      $result['user'] = $users[0];
      $of = $this->SQL->database->getRelationshipsOf('users',$result['user']['id']);
      $options = [];
      if(isset($of['groups'])){
        foreach($of['groups'] as $groupID => $group){
          $result['groups'][$groupID] = $group;
          $groupRelations = $this->SQL->database->getRelationshipsOf('groups',$groupID);
          if(isset($groupRelations['roles'])){
            foreach($groupRelations['roles'] as $roleID => $role){
              $role['permissions'] = json_decode($role['permissions'],true);
              $result['roles'][$roleID] = $role;
              if($role['permissions'] != null){
                foreach($role['permissions'] as $permission => $value){
                  if(!isset($result['permissions'][$permission]) || $result['permissions'][$permission] < $value){
                    $result['permissions'][$permission] = $value;
                  }
                }
              }
            }
          }
          if(isset($groupRelations['options'])){
            $options = array_merge($options,$groupRelations['options']);
          }
        }
      }
      if(isset($of['options'])){
        $options = array_merge($options,$of['options']);
      }
      foreach($options as $optionID => $option){
        $option['options'] = json_decode($option['options'],true);
        if(isset($result['options'][$option['name']])){
          $result['options'][$option['name']] = array_replace_recursive($result['options'][$option['name']],$option['options']);
        } else { $result['options'][$option['name']] = $option['options']; }
        $result['options'][$option['name']]['id'] = $option['id'];
      }
      return $result;
    } else { return false; }
  }

  public function deactivate($username){
    if($user = $this->getUser($username)){
      if($user['user']['status'] < 3){
        if($query = $this->SQL->database->prepare('update','users', ['status'])){
          if($this->SQL->database->query($query,['status' => 3, 'id' => $user['user']['id']])){
            $this->log("[".$username."] was disabled");
            $body = 'Dear '.$user['user']['name'].',<br>';
            $body .= 'Your account was disabled by an administrator.';
            $extra = [
              'title' => 'Account Disabled',
              'subject' => 'Account Disabled',
            ];
            if($this->SMTP->send($username,$body,$extra)){
              $this->log("[".$username."] has been notified");
              $this->SQL->database->createRelationship(['users' => $user['user']['id']],null,['alert' => ['name' => 'Account Disabled', 'text' => 'User has been disabled', 'icon' => 'fa-solid fa-exclamation', 'color' => 'danger']]);
              $this->SQL->database->createRelationship(['users' => $user['user']['id']],null,['status' => ['name' => 'Disabled', 'text' => 'User has been disabled', 'icon' => 'fa-solid fa-exclamation', 'color' => 'danger']]);
              return true;
            } else { $this->log("Unable to send notification"); }
          } else { $this->log("[".$username."] Unable to disable user"); }
        } else { $this->log("[".$username."] Unable to disable user"); }
      } else { $this->log("[".$username."] Unable to disable user"); }
    } else { $this->log("[".$username."] Unable to disable user"); }
    return false;
  }

  public function reactivate($username){
    if($user = $this->getUser($username)){
      if($user['user']['status'] > 2){
        if($query = $this->SQL->database->prepare('update','users', ['status'])){
          if($this->SQL->database->query($query,['status' => 2, 'id' => $user['user']['id']])){
            $user['user']['status'] = 2;
          } else { $this->log("[".$username."] Unable to reactivate user"); }
        } else { $this->log("[".$username."] Unable to reactivate user"); }
      }
      if($user['user']['status'] >= 2 && $user['user']['keyActivation'] == null){
        if($query = $this->SQL->database->prepare('update','users', ['keyActivation'])){
          $keyActivation = $this->genKey();
          $hash = password_hash($keyActivation, PASSWORD_BCRYPT, array("cost" => 10));
          if($this->SQL->database->query($query,['keyActivation' => $hash, 'id' => $user['user']['id']])){
            $this->log("[".$username."] can now be reactivated");
            $body = 'Dear '.$user['user']['name'].',<br>';
            $body .= 'Your account was enabled by an administrator.<br><br>';
            $body .= 'To activate your account, click the link below and sign in with your account.<br><br>';
            $body .= '<a href="'.$this->Settings['url'].'?key='.$keyActivation.'" style="color:#0088cc" class="arrow-right">Click here to activate your account</a>';
            $extra = [
              'title' => 'Account Enabled',
              'subject' => 'Account Enabled',
            ];
            if($this->SMTP->send($username,$body,$extra)){
              $this->log("[".$username."] has been notified");
              $this->SQL->database->createRelationship(['users' => $user['user']['id']],null,['alert' => ['name' => 'Account Enabled', 'text' => 'User has been enabled', 'icon' => 'fa-solid fa-check', 'color' => 'success']]);
              $this->SQL->database->createRelationship(['users' => $user['user']['id']],null,['status' => ['name' => 'Enabled', 'text' => 'User has been enabled', 'icon' => 'fa-solid fa-check', 'color' => 'success']]);
              return true;
            } else { $this->log("Unable to send notification"); }
          } else { $this->log("[".$username."] Unable to reactivate user"); }
        } else { $this->log("[".$username."] Unable to reactivate user"); }
      } else { $this->log("[".$username."] Unable to reactivate user"); }
    } else { $this->log("[".$username."] Unable to reactivate user"); }
    return false;
  }

  protected function activate($key){
    if($this->User['status'] != 1 && password_verify($key,$this->User['keyActivation'])){
      if($query = $this->SQL->database->prepare('update','users', ['status','keyActivation'])){
        if($this->SQL->database->query($query,['status' => 1, 'keyActivation' => null, 'id' => $this->User['id']])){
          $this->User['status'] = 1;
          $this->User['keyActivation'] = null;
          $this->log("[".$this->User['username']."] was activated");
          $body = 'Dear '.$this->User['name'].',<br>';
          $body .= 'Your account was successfully activated.<br>';
          $extra = [
            'title' => 'Activation Successful',
            'subject' => 'Activation Successful',
          ];
          if($this->SMTP->send($this->User['username'],$body,$extra)){
            $this->SQL->database->createRelationship(['users' => $this->User['id']],null,['alert' => ['name' => 'Activation Successful', 'text' => 'User has been activated', 'icon' => 'fa-solid fa-check', 'color' => 'success']]);
            $this->SQL->database->createRelationship(['users' => $this->User['id']],null,['status' => ['name' => 'Activated', 'text' => 'User has been activated', 'icon' => 'fa-solid fa-check', 'color' => 'success']]);
          } else {
            $this->log("Unable to send notification");
          }
        }
      }
    }
  }

  protected function genKey(){
    return sha1(mt_rand(10000,99999).time().$this->User['username']);
  }

  public function login($username, $password){
    $this->log("[".$username."] attempting to connect");
    if($user = $this->getUser($username)){
      switch($user['user']['type']){
        case'imap':
          $login = $this->IMAP->login($username,$password);
          break;
        case'smtp':
          $login = $this->SMTP->login($username,$password,$this->Settings['smtp']['host'],$this->Settings['smtp']['port'],$this->Settings['smtp']['encryption']);
          break;
        case'sql':
          $login = password_verify($password,$user['user']['password']);
          break;
        default:
          $login = false;
          break;
      }
      if($login){
        $this->setUser($user['user']);
        $this->log("[".$username."] was authenticated");
        if($this->User['status'] == 1){
          if($query = $this->SQL->database->prepare('update','users', ['attempts','keyActivation'])){
            if(!$this->SQL->database->query($query,['attempts' => 0,'keyActivation' => null, 'id' => $user['user']['id']])){
              $this->log("[".$username."] Unable to update login attempts");
            }
          } else { $this->log("[".$username."] Unable to update login attempts"); }
        }
      } else {
        if($user['user']['attempts'] < 3){
          if($query = $this->SQL->database->prepare('update','users', ['attempts'])){
            $user['user']['attempts'] = $user['user']['attempts']+1;
            if($this->SQL->database->query($query,['attempts' => $user['user']['attempts'], 'id' => $user['user']['id']])){
              $this->log("[".$username."] Attempted to login for the ".$user['user']['attempts']." time");
            } else { $this->log("[".$username."] Unable to update login attempts"); }
          } else { $this->log("[".$username."] Unable to update login attempts"); }
        } else {
          if($user['user']['keyActivation'] == null){
            if($query = $this->SQL->database->prepare('update','users', ['status','keyActivation'])){
              $keyActivation = $this->genKey();
              $hash = password_hash($keyActivation, PASSWORD_BCRYPT, array("cost" => 10));
              if($this->SQL->database->query($query,['status' => 2,'keyActivation' => $hash, 'id' => $user['user']['id']])){
                $this->log("[".$username."] was deativated for suspicious activity");
                $body = 'Dear '.$user['user']['name'].',<br>';
                $body .= 'Your account was disabled for security reasons.';
                $body .= 'We detected some unusual activity. Your account has made multiple attempts at authenticating on '.$this->Settings['name'].'.<br><br>';
                $body .= 'To activate your account, click the link below and sign in with your account.<br><br>';
                $body .= '<a href="'.$this->Settings['url'].'?key='.$keyActivation.'" style="color:#0088cc" class="arrow-right">Click here to activate your account</a>';
                $extra = [
                  'title' => 'Suspicious Activity',
                  'subject' => 'Suspicious Activity',
                  'bcc' => $this->Settings['administration'],
                ];
                if($this->SMTP->send($username,$body,$extra)){
                  $this->log("[".$username."] was notified about the suspicious activity");
                  $this->SQL->database->createRelationship(['users' => $user['user']['id']],null,['alert' => ['name' => 'Suspicious Activity', 'text' => 'User has been disabled', 'icon' => 'fa-solid fa-exclamation', 'color' => 'danger']]);
                  $this->SQL->database->createRelationship(['users' => $user['user']['id']],null,['status' => ['name' => 'Disabled', 'text' => 'User has been disabled', 'icon' => 'fa-solid fa-exclamation', 'color' => 'danger']]);
                } else { $this->log("Unable to send notification"); }
              } else { $this->log("[".$username."] Unable to lock user"); }
            } else { $this->log("[".$username."] Unable to lock user"); }
          }
        }
      }
      return $login;
    } else { return false; }
  }

  public function isAllowed($permission, $level = 1){
    return ((isset($this->Permissions[$permission]) && $this->Permissions[$permission] >= $level) || (isset($this->Permissions['isAdministrator']) && $this->Permissions['isAdministrator']));
  }

  public function isActivated(){
    return $this->isLogin() && isset($this->User['status']) && $this->User['status'] > 0;
  }

  public function isDeactivated(){
    return $this->isLogin() && isset($this->User['status']) && $this->User['status'] > 1;
  }

  public function isLogin(){
    return session_status() == PHP_SESSION_ACTIVE && !empty($_SESSION);
  }

  public function logout(){
    if($this->sessionID != null){
      $this->log("[".$_SESSION[$this->sessionID]."] is disconnected");
      $statement = $this->SQL->database->prepare('delete','sessions',['conditions' => ['sessionID' => '=']]);
      $this->SQL->database->query($statement,$this->sessionID);
    }
    if(isset($_SESSION) && !empty($_SESSION)){
      foreach($_SESSION as $key => $value){ unset($_SESSION[$key]); }
    }
    if(isset($_SERVER['HTTP_COOKIE'])){
      $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
      foreach($cookies as $cookie){
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        unset($_COOKIE[$name]);
        setcookie($name, null, -1);
        setcookie($name, null, -1, '/');
      }
    }
    session_unset();
    session_destroy();
    if(!$this->isLogin()){
      return [
        "success" => $this->Fields['Logged out'],
        "output" => [
          "status" => $this->isLogin(),
        ],
      ];
    } else {
      return [
        "error" => $this->Fields['Unable to logout'],
        "output" => [
          "status" => $this->isLogin(),
        ],
      ];
    }
  }

  protected function error($log = [], $force = false){
    $this->log(json_encode($log, JSON_PRETTY_PRINT),$force);
    exit();
  }

  protected function log($txt = " ", $force = false){
    if(is_bool($txt)){ $txt = $txt ? 'true' : 'false'; }
    if(!is_string($txt)){ $txt = json_encode($txt, JSON_PRETTY_PRINT); }
    $txt = "[".date("Y-m-d H:i:s")."][".$this->getClientIP()."]".$txt;
    if(defined('STDIN')){ echo $txt."\n"; }
    if($force || $this->Logger){
      return file_put_contents($this->Log, $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
    }
  }

  protected function getClientBrowser(){
    $t = strtolower($_SERVER['HTTP_USER_AGENT']);
    $t = " " . $t;
    if     (strpos($t, 'opera'     ) || strpos($t, 'opr/')     ) return 'Opera'            ;
    elseif (strpos($t, 'edge'      )                           ) return 'Edge'             ;
    elseif (strpos($t, 'chrome'    )                           ) return 'Chrome'           ;
    elseif (strpos($t, 'safari'    )                           ) return 'Safari'           ;
    elseif (strpos($t, 'firefox'   )                           ) return 'Firefox'          ;
    elseif (strpos($t, 'msie'      ) || strpos($t, 'trident/7')) return 'Internet Explorer';
    return 'Unkown';
  }

	public function getClientIP(){
	  $ipaddress = '';
	  if(getenv('HTTP_CLIENT_IP')){
	    $ipaddress = getenv('HTTP_CLIENT_IP');
	  } elseif(getenv('HTTP_X_FORWARDED_FOR')){
	    $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	  } elseif(getenv('HTTP_X_FORWARDED')){
	    $ipaddress = getenv('HTTP_X_FORWARDED');
	  } elseif(getenv('HTTP_FORWARDED_FOR')){
	    $ipaddress = getenv('HTTP_FORWARDED_FOR');
	  } elseif(getenv('HTTP_FORWARDED')){
	    $ipaddress = getenv('HTTP_FORWARDED');
	  } elseif(getenv('REMOTE_ADDR')){
	    $ipaddress = getenv('REMOTE_ADDR');
    } elseif(defined('STDIN')){
      $ipaddress = 'LOCALHOST';
	  } else {
	    $ipaddress = 'UNKNOWN';
		}
	  return $ipaddress;
	}
}
