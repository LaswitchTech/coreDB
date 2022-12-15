<?php

//Import BaseController class into the global namespace
use LaswitchTech\phpAPI\BaseController;

//Import Auth class into the global namespace
use LaswitchTech\phpAUTH\Auth;

class DiagnosticController extends BaseController {

  public function authAction() {
    if(!define('AUTH_OUTPUT_TYPE')){ define('AUTH_OUTPUT_TYPE','STRING'); }
    $Auth = new Auth();
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    if (strtoupper($requestMethod) == 'GET') {
      try {
        $responseData = json_encode($Auth->getDiag(),JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
      } catch (Error $e) {
        $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
      }
    } else {
      $strErrorDesc = 'Method not supported';
      $strErrorHeader = 'HTTP/1.1 405 Method Not Allowed';
    }
    if (!$strErrorDesc) {
      $this->sendOutput(
        $responseData,
        array('Content-Type: application/json', 'HTTP/1.1 200 OK')
      );
    } else {
      $this->sendOutput(json_encode(array('error' => $strErrorDesc)),
        array('Content-Type: application/json', $strErrorHeader)
      );
    }
  }

  public function constantAction() {
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    if(strtoupper($requestMethod) == 'GET'){
      try {
        $constantArr = [];
        foreach(get_defined_constants() as $key => $value){
          if(str_starts_with($key, 'COREDB') || str_starts_with($key, 'ROUTER') || str_starts_with($key, 'SMTP') || str_starts_with($key, 'DB') || str_starts_with($key, 'AUTH') || str_starts_with($key, 'ROOT')){
            $constantArr[$key] = $value;
          }
        }
        $responseData = json_encode($constantArr,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
      } catch (Error $e) {
        $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
      }
    } else {
      $strErrorDesc = 'Method not supported';
      $strErrorHeader = 'HTTP/1.1 405 Method Not Allowed';
    }
    if (!$strErrorDesc) {
      $this->sendOutput(
        $responseData,
        array('Content-Type: application/json', 'HTTP/1.1 200 OK')
      );
    } else {
      $this->sendOutput(json_encode(array('error' => $strErrorDesc)),
        array('Content-Type: application/json', $strErrorHeader)
      );
    }
  }
}
