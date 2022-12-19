<?php

//Import BaseCommand class into the global namespace
use LaswitchTech\phpCLI\BaseCommand;

class ApiCommand extends BaseCommand {

  protected $Token = null;
  protected $URL = null;

  public function __construct(){

    // Execute parent Constructor
    parent::__construct();

    // Retrieve CLI User's Token
    $userModel = new UserModel();
    $arrUsers = $userModel->getUser('cli');
    if(count($arrUsers) > 0){
      $this->Token = base64_encode($arrUsers[0]['token']);
    }

    // $this->Token = "ZTZjM2RhNWIyMDY2MzRkN2YzZjM1ODZkNzQ3ZmZkYjM2YjVjNjc1NzU3YjM4MGM2YTVmZTVjNTcwYzcxNDM0OQ==";

    // Retrieve API URL
    if(defined("COREDB_URL")){ $this->URL = COREDB_URL; }
  }

  public function getAction($argv){
    if($this->Token != null && $this->URL != null){
      // Get API Parameters
      $parameters = $argv[0];
      unset($argv[0]);
      $argv = array_values($argv);

      // Parse $argv
      $data = [];
      foreach($argv as $arg){
        $needle = explode('=',$arg);
        if(count($needle) > 1){
          $data[$needle[0]] = $needle[1];
        } else {
          $data[$needle[0]] = true;
        }
      }

      // cURL Request
      $response = $this->call('GET', $this->Token . '@' . $this->URL . 'api.php/' . $parameters, $data);

      // Output results
      $this->warning(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    } else {
      $this->error('API not ready');
    }
  }

  public function postAction($argv){
    if($this->Token != null && $this->URL != null){
      // Get API Parameters
      $parameters = $argv[0];
      unset($argv[0]);
      $argv = array_values($argv);

      // Parse $argv
      $data = [];
      foreach($argv as $arg){
        $needle = explode('=',$arg);
        if(count($needle) > 1){
          $data[$needle[0]] = $needle[1];
        } else {
          $data[$needle[0]] = true;
        }
      }

      // cURL Request
      $response = $this->call('POST', $this->Token . '@' . $this->URL . 'api.php/' . $parameters, $data);

      // Output results
      $this->output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    } else {
      $this->error('API not ready');
    }
  }

  protected function call($method, $url, $data = false){

    // Init cURL
    $curl = curl_init();

    // Enable Verbose
    curl_setopt($curl, CURLOPT_VERBOSE, true);
    $log = fopen($this->Path . '/tmp/curl.log', 'w+');
    curl_setopt($curl, CURLOPT_STDERR, $log);

    // Handle Method
    switch (strtoupper($method)){
      case"POST":
        curl_setopt($curl, CURLOPT_POST, 1);
        if($data){
          curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        break;
      case"PUT":
        curl_setopt($curl, CURLOPT_PUT, 1);
        break;
      default:
        if($data){
          $url = sprintf("%s?%s", $url, http_build_query($data));
        }
    }

    // Optional Authentication:
    $auth = $url;
    if(str_contains($auth, '@')){
      $auth = explode('@',$auth)[0];
      if(str_contains($auth, ':')){
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $auth);
      } else {
        $authorization = "Authorization: Bearer " . $auth;
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization));
      }
      $url = str_replace($auth.'@','',$url);
    }

    // Configure cURL
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
    curl_setopt($curl, CURLOPT_UNRESTRICTED_AUTH, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL
    $result = curl_exec($curl);

    // Debug
    var_dump($method,$url,$data);
    var_dump($auth,$authorization,$result);
    // var_dump(curl_getinfo($curl));

    // Close cURL Request
    curl_close($curl);

    // Return
    return json_decode($result,true);
  }
}
