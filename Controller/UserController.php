<?php

//Import BaseController class into the global namespace
use LaswitchTech\phpAPI\BaseController;

//Import Auth class into the global namespace
use LaswitchTech\coreDB\Auth;

//Import phpSMTP class into the global namespace
use LaswitchTech\SMTP\phpSMTP;

class UserController extends BaseController {

  public function recoverAction(){
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if (strtoupper($requestMethod) == 'GET') {
      try {
        $userModel = new UserModel();
        if(isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']) {
          if($userModel->recoverUser($arrQueryStringParams['id'])){
            $responseData = json_encode('Recovery notification sent');
          } else {
            $strErrorDesc = 'Unable to sent the notification.';
            $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
          }
        } else {
          $strErrorDesc = 'User not provided.';
          $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
      } catch (Error $e) {
        $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
      }
    } else {
      $strErrorDesc = 'Method not supported';
      $strErrorHeader = 'HTTP/1.1 405 Method Not Allowed';
    }
    if (!$strErrorDesc) {
      $this->output(
        $responseData,
        array('Content-Type: application/json', 'HTTP/1.1 200 OK')
      );
    } else {
      $this->output(json_encode(array('error' => $strErrorDesc)),
        array('Content-Type: application/json', $strErrorHeader)
      );
    }
  }

  public function recoveredAction() {
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    $arrQueryStringBody = $this->getQueryStringBody();
    if (strtoupper($requestMethod) == 'POST') {
      try {
        if(isset($arrQueryStringParams['id'])){
          if(isset($arrQueryStringBody['token'],$arrQueryStringBody['password'],$arrQueryStringBody['confirm'])){
            $userModel = new UserModel();

            // Validate password
            $uppercase = preg_match('@[A-Z]@', $arrQueryStringBody['password']);
            $lowercase = preg_match('@[a-z]@', $arrQueryStringBody['password']);
            $number    = preg_match('@[0-9]@', $arrQueryStringBody['password']);
            $specialChars = preg_match('@[^\w]@', $arrQueryStringBody['password']);
            if($uppercase && $lowercase && $number && $specialChars && strlen($arrQueryStringBody['password']) >= 8){

              // Validate confirm
              if($arrQueryStringBody['password'] === $arrQueryStringBody['confirm']){

                // Complete Recovery
                if($userModel->recoveredUser($arrQueryStringParams['id'],$arrQueryStringBody['token'],$arrQueryStringBody['password'])){
                  $responseData = json_encode('Account Recovered');
                } else {
                  $strErrorDesc = 'Unable to recover account.';
                  $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                }
              } else {
                $strErrorDesc = 'Passwords must match.';
                $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
              }
            } else {
              $strErrorDesc = 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.';
              $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }
          } else {
            $strErrorDesc = 'Unable to identify your layout';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
          }
        } else {
          $strErrorDesc = 'User not provided';
          $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
      } catch (Error $e) {
        $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
      }
    } else {
      $strErrorDesc = 'Method not supported';
      $strErrorHeader = 'HTTP/1.1 405 Method Not Allowed';
    }
    if (!$strErrorDesc) {
      $this->output(
        $responseData,
        array('Content-Type: application/json', 'HTTP/1.1 200 OK')
      );
    } else {
      $this->output(json_encode(array('error' => $strErrorDesc)),
        array('Content-Type: application/json', $strErrorHeader)
      );
    }
  }

  public function listAction(){
    $Auth = new Auth();
    $Auth->isAuthorized("user/list");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if (strtoupper($requestMethod) == 'GET') {
      try {
        $userModel = new UserModel();
        $limit = 25;
        if(isset($arrQueryStringParams['limit'])){
          $limit = intval($arrQueryStringParams['limit']);
        }
        $arrUsers = $userModel->getUsers(intval($limit));
        if(count($arrUsers) > 0){
          $responseData = json_encode($arrUsers);
        } else {
          $strErrorDesc = 'Users Not Found.';
          $strErrorHeader = 'HTTP/1.1 404 Not Found';
        }
      } catch (Error $e) {
        $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
      }
    } else {
      $strErrorDesc = 'Method not supported';
      $strErrorHeader = 'HTTP/1.1 405 Method Not Allowed';
    }
    if (!$strErrorDesc) {
      $this->output(
        $responseData,
        array('Content-Type: application/json', 'HTTP/1.1 200 OK')
      );
    } else {
      $this->output(json_encode(array('error' => $strErrorDesc)),
        array('Content-Type: application/json', $strErrorHeader)
      );
    }
  }

  public function getAction(){
    $Auth = new Auth();
    $Auth->isAuthorized("user/get");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if (strtoupper($requestMethod) == 'GET') {
      try {
        $userModel = new UserModel();
        if (isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']) {
          $arrUsers = $userModel->getUser($arrQueryStringParams['id']);
          if(count($arrUsers) > 0){
            $responseData = json_encode($arrUsers);
          } else {
            $strErrorDesc = 'User Not Found.';
            $strErrorHeader = 'HTTP/1.1 404 Not Found';
          }
        } else {
          $strErrorDesc = 'User not provided.';
          $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
      } catch (Error $e) {
        $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
      }
    } else {
      $strErrorDesc = 'Method not supported';
      $strErrorHeader = 'HTTP/1.1 405 Method Not Allowed';
    }
    if (!$strErrorDesc) {
      $this->output(
        $responseData,
        array('Content-Type: application/json', 'HTTP/1.1 200 OK')
      );
    } else {
      $this->output(json_encode(array('error' => $strErrorDesc)),
        array('Content-Type: application/json', $strErrorHeader)
      );
    }
  }

  public function addAction(){
    $Auth = new Auth();
    $Auth->isAuthorized("user/add");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if(strtoupper($requestMethod) == 'GET'){
      try {
        $userModel = new UserModel();
        if(isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']){
          $phpSMTP = new phpSMTP();
          if($phpSMTP->isConnected()){
            $password = $this->hex(6);
            $arrUsers = $userModel->getUser($arrQueryStringParams['id']);
            if(count($arrUsers) <= 0){
              $arrUsers = $userModel->addUser($arrQueryStringParams['id'],$password);
              if($arrUsers){
                $arrUsers = $userModel->getUser($arrQueryStringParams['id']);
                if(count($arrUsers) > 0){
                  if($phpSMTP->send([
                    "TO" => $arrQueryStringParams['id'],
                    "SUBJECT" => "Account Activation",
                    "TITLE" => "Account Activation",
                    "MESSAGE" => "Your account has been created. Here is your password: ".$password,
                  ])){
                    $responseData = json_encode($arrUsers);
                  } else {
                    $strErrorDesc = 'Unable to send email to user.';
                    $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                  }
                } else {
                  $strErrorDesc = 'User Not Found.';
                  $strErrorHeader = 'HTTP/1.1 404 Not Found';
                }
              } else {
                $strErrorDesc = 'Unable to create this user.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
              }
            } else {
              $strErrorDesc = 'User already exist.';
              $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }
          } else {
            $strErrorDesc = 'Unable to connect to SMTP server.';
            $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
          }
        } else {
          $strErrorDesc = 'User not provided.';
          $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
      } catch (Error $e){
        $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
      }
    } else {
      $strErrorDesc = 'Method not supported';
      $strErrorHeader = 'HTTP/1.1 405 Method Not Allowed';
    }
    if(!$strErrorDesc){
      $this->output(
        $responseData,
        array('Content-Type: application/json', 'HTTP/1.1 200 OK')
      );
    } else {
      $this->output(json_encode(array('error' => $strErrorDesc)),
        array('Content-Type: application/json', $strErrorHeader)
      );
    }
  }

  public function deleteAction(){
    $Auth = new Auth();
    $Auth->isAuthorized("user/delete");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if (strtoupper($requestMethod) == 'GET') {
      try {
        $userModel = new UserModel();
        if (isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']) {
          $arrUsers = $userModel->deleteUser($arrQueryStringParams['id']);
          if($arrUsers){
            $responseData = json_encode($arrUsers);
          } else {
            $strErrorDesc = 'User Not Found.';
            $strErrorHeader = 'HTTP/1.1 404 Not Found';
          }
        } else {
          $strErrorDesc = 'User not provided.';
          $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
      } catch (Error $e) {
        $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
      }
    } else {
      $strErrorDesc = 'Method not supported';
      $strErrorHeader = 'HTTP/1.1 405 Method Not Allowed';
    }
    if (!$strErrorDesc) {
      $this->output(
        $responseData,
        array('Content-Type: application/json', 'HTTP/1.1 200 OK')
      );
    } else {
      $this->output(json_encode(array('error' => $strErrorDesc)),
        array('Content-Type: application/json', $strErrorHeader)
      );
    }
  }

  protected function hex($length = 16){
    return bin2hex(openssl_random_pseudo_bytes($length));
  }
}
