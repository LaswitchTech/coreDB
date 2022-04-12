<?php

// Import Librairies
require_once dirname(__FILE__,3) . '/src/lib/api.php';
require_once dirname(__FILE__,3) . '/src/lib/url.php';

class Request {

  protected $URL;
  protected $API;
  protected $Class = 'API';
  protected $Method = 'init';
  protected $Return;
  protected $Data = [];
  protected $Blacklist = ['SQL','IMAP','SMTP','PHPMAILER','URLparser','Application','Auth','Database','CLI','Request','Command','INSTALLER','Exception','TEST','MAILER'];

  public function __construct(){
    $this->URL = new URLparser();
    // Handling POST
    if(!empty($_POST)){
    	// Decoding
    	foreach($_POST as $key => $value){ $_POST[$key] = $this->URL->decode($value); }
    	// Parse
    	foreach($_POST as $key => $value){ $_POST[$key] = $this->URL->parse($value); }
      // Initialize Class
      if(isset($_POST['request']) && !in_array($_POST['request'],$this->Blacklist) && class_exists($_POST['request'])){ $this->Class = strtoupper($_POST['request']); }
    	// Initialize Method
    	if(isset($_POST['type'])){ $this->Method = $_POST['type']; }
    	// Initialize Data
    	if(isset($_POST['data'])){ $this->Data = $_POST['data']; }
  		// Start API
  		$this->API = new $this->Class();
      // Installation Verification
  		if($this->API->isInstalled()){
  			// Maintenance Verification
  			if((!isset($this->API->Settings['maintenance']))||(!$this->API->Settings['maintenance'])){
  				// Check Login & Status
  				if(($this->API->Auth->isLogin() && $this->API->Auth->isActivated() && !$this->API->Auth->isDeactivated()) || $this->Method == 'init'){
            if(method_exists($this->API,$this->Method)){ $exec = $this->Method;$this->Return = $this->API->$exec($this->Data); }
            else {
              $this->Return = [
                "error" => $this->API->getField("Unknown request"),
                "code" => 404,
              ];
            }
  				} else {
            $this->Return = $this->API->logout();
  					// $this->Return = [
  					// 	"error" => $this->API->getField("Not logged in"),
  					// 	"code" => 403,
  					// ];
  				}
  			} else {
  				$this->Return = [
  					"error" => $this->API->getField("Server under maintenance"),
  					"code" => 500,
  				];
  			}
  		} else { $this->Return = $this->API->init($this->Data); }
  		// Add Diagnostic Data
      if($this->API->isDebugger() && isset($this->Return['error'])){
        $this->Return['post'] = $_POST;
        $this->Return['request'] = [
          "class" => $this->Class,
          "method" => $this->Method,
          "data" => $this->Data,
        ];
      }
      // Print Return
  		echo json_encode($this->Return, JSON_PRETTY_PRINT);
  	}
  }
}
