<?php

// Import Librairies
require_once dirname(__FILE__,3) . '/src/lib/database.php';

class SQL{

  public $database;
  protected $language = [];

	public function __construct($settings = [],$language = []){
    if(!empty($settings)){
      $this->database = $this->connect($settings['host'],$settings['username'],$settings['password'],$settings['database']);
    }
    if(!empty($language)){ $this->language = $language; }
	}

  public function connect($host = 'localhost', $username = 'root', $password = '', $database = ''){
    return new Database($host, $username, $password, $database);
  }
}
