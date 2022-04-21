<?php

// Import Librairies
require_once dirname(__FILE__,3) . '/src/lib/api.php';

class Application extends API{

  public function __construct(){
    parent::__construct();

    // Setup Logger
    $this->Log = dirname(__FILE__,3) . "/tmp/application.log";
		if(isset($this->Settings['log']['application']['status'])){ $this->Logger = $this->Settings['log']['application']['status']; }
    if(isset($this->Settings['log']['application']['location'])){ $this->Log = $this->Settings['log']['application']['location']; }
  }

	public function start(){
		require_once dirname(__FILE__,3) . '/src/templates/template.php';
	}
}
