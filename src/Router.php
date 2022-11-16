<?php

//Declaring namespace
namespace LaswitchTech\coreDB;

//Import phpRouter class into the global namespace
use LaswitchTech\phpRouter\phpRouter;
use LaswitchTech\phpAUTH\Auth;

class Router extends phpRouter {

  public $Auth = null;

  public function __construct(){
    parent::__construct();
    // $this->Auth = new Auth("SESSION");
  }

  public function getGravatar( $email, $s = 200, $d = 'mp', $r = 'g', $img = false, $atts = array() ) {
		$url = 'https://www.gravatar.com/avatar/';
		$url .= md5( strtolower( trim( $email ) ) );
		$url .= "?s=$s&d=$d&r=$r";
		if ( $img ) {
			$url = '<img src="' . $url . '"';
			foreach ( $atts as $key => $val )
				$url .= ' ' . $key . '="' . $val . '"';
			$url .= ' />';
		}
		return $url;
	}

  // Method: POST, PUT, GET etc
  // Data: array("param" => "value") ==> index.php?param=value
  public function callAPI($method, $url, $data = false){
    $curl = curl_init();
    switch($method){
      case "POST":
        curl_setopt($curl, CURLOPT_POST, 1);
        if($data){ curl_setopt($curl, CURLOPT_POSTFIELDS, $data); }
        break;
      case "PUT":
        curl_setopt($curl, CURLOPT_PUT, 1);
        break;
      default:
        if($data){ $url = sprintf("%s?%s", $url, http_build_query($data)); }
    }
    // Optional Authentication:
    // curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    // curl_setopt($curl, CURLOPT_USERPWD, "username:password");
    // Continue
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
  }

  public function getWallpaper(){
    $return = json_decode($this->callAPI("GET","https://go-apod.herokuapp.com/apod"),true);
    if(isset($return['hdurl'])){
      return $return['hdurl'];
    } elseif(isset($return['url'])){
      return $return['url'];
    } else {
      return null;
    }
  }
}
