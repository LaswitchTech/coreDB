<?php

//Import BaseController class into the global namespace
use LaswitchTech\phpAPI\BaseController;

//Import Auth class into the global namespace
use LaswitchTech\phpAUTH\Auth;

class IconController extends BaseController {

  public function listAction() {
    $Auth = new Auth();
    $Auth->isAuthorized("icon/list");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    if (strtoupper($requestMethod) == 'GET') {
      try {
        $arrIcons = [];
        foreach(scandir($this->Path . "/vendor/twbs/bootstrap-icons/icons") as $key => $name){
          if(!in_array($name,['.','..'])){ $arrIcons[] = str_replace('.svg','',$name); }
        }
        $responseData = json_encode($arrIcons);
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
