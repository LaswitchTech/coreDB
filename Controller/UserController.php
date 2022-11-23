<?php

//Import BaseController class into the global namespace
use LaswitchTech\phpAPI\BaseController;

//Import Auth class into the global namespace
use LaswitchTech\phpAUTH\Auth;

class UserController extends BaseController {

  public function tokenAction() {
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if (strtoupper($requestMethod) == 'GET') {
      try {
        $userModel = new UserModel();
        if (isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']) {
          $arrUsers = $userModel->getUser(intval($arrQueryStringParams['id']));
          if(count($arrUsers) > 0){
            $responseData = json_encode(base64_encode($arrUsers[0]['token']));
          } else {
            $strErrorDesc = 'User not found.';
            $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
          }
        } else {
          $strErrorDesc = 'User not provided.';
          $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
        }
      } catch (Error $e) {
        $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
      }
    } else {
      $strErrorDesc = 'Method not supported';
      $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
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
