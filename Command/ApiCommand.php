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
      $this->Token = $arrUsers[0]['token'];
    }

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
      $this->warning($this->Token . '@' . $this->URL . $parameters);
      $response = $this->call('GET', $this->Token . '@' . $this->URL . $parameters, $data);

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
      $response = $this->call('POST', $this->Token . '@' . $this->URL . $parameters, $data);

      // Output results
      $this->output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    } else {
      $this->error('API not ready');
    }
  }
}
