<?php

//Import BaseController class into the global namespace
use LaswitchTech\phpAPI\BaseController;

//Import Auth class into the global namespace
use LaswitchTech\coreDB\Auth;

//Import phpCSRF Class into the global namespace
use LaswitchTech\phpCSRF\phpCSRF;

//Import Configurator class into the global namespace
use LaswitchTech\coreDB\Configurator;

class UserController extends BaseController {

  protected $Configurator = null;
  protected $CSRF = null;

  public function __construct(){

    // Initiate Configurator
    $this->Configurator = new Configurator();

    // Initiate phpCSRF
    $this->CSRF = new phpCSRF();

    // Initiate Parent Constructor
    parent::__construct();
  }

  public function recoverAction(){
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if (strtoupper($requestMethod) == 'GET') {
      try {
        $userModel = new UserModel();
        if($this->CSRF->validate()){
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
        } else {
          $strErrorDesc = 'Unable to certify request.';
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
    $arrPostParams = $this->getPostParams();
    if (strtoupper($requestMethod) == 'POST') {
      try {
        if($this->CSRF->validate()){
          if(isset($arrQueryStringParams['id'])){
            if(isset($arrPostParams['token'],$arrPostParams['password'],$arrPostParams['confirm'])){
              $userModel = new UserModel();

              // Validate password
              $uppercase = preg_match('@[A-Z]@', $arrPostParams['password']);
              $lowercase = preg_match('@[a-z]@', $arrPostParams['password']);
              $number    = preg_match('@[0-9]@', $arrPostParams['password']);
              $specialChars = preg_match('@[^\w]@', $arrPostParams['password']);
              if($uppercase && $lowercase && $number && $specialChars && strlen($arrPostParams['password']) >= 8){

                // Validate confirm
                if($arrPostParams['password'] === $arrPostParams['confirm']){

                  // Complete Recovery
                  if($userModel->recoveredUser($arrQueryStringParams['id'],$arrPostParams['token'],$arrPostParams['password'])){
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
        } else {
          $strErrorDesc = 'Unable to certify request.';
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
        if($this->CSRF->validate()){
          if(isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']){
            $arrUsers = $userModel->getUser($arrQueryStringParams['id']);
            if(count($arrUsers) <= 0){
              $arrUsers = $userModel->addUser($arrQueryStringParams['id']);
              if($arrUsers){
                $arrUsers = $userModel->getUser($arrQueryStringParams['id']);
                if(count($arrUsers) > 0){
                  $arrUser = $arrUsers[0];
                  $token = $userModel->deactivateUser($arrUser['username']);
                  if($token){
                    $responseData = json_encode($arrUser);
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
            $strErrorDesc = 'User not provided.';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
          }
        } else {
          $strErrorDesc = 'Unable to certify request.';
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
        if($this->CSRF->validate()){
          if(isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']){
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
        } else {
          $strErrorDesc = 'Unable to certify request.';
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

  public function enableAction(){
    $Auth = new Auth();
    $Auth->isAuthorized("user/enable");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if(strtoupper($requestMethod) == 'GET'){
      try {
        $userModel = new UserModel();
        if($this->CSRF->validate()){
          if(isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']){
            $arrUsers = $userModel->deactivateUser($arrQueryStringParams['id']);
            if($arrUsers){
              $arrUsers = $userModel->getUser($arrQueryStringParams['id']);
              if(count($arrUsers) > 0){
                $arrUser = $arrUsers[0];
                $responseData = json_encode($arrUser);
              } else {
                $strErrorDesc = 'Unable to send email to user.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
              }
            } else {
              $strErrorDesc = 'User Not Found.';
              $strErrorHeader = 'HTTP/1.1 404 Unprocessable Entity';
            }
          } else {
            $strErrorDesc = 'User not provided.';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
          }
        } else {
          $strErrorDesc = 'Unable to certify request.';
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

  public function disableAction(){
    $Auth = new Auth();
    $Auth->isAuthorized("user/disable");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if(strtoupper($requestMethod) == 'GET'){
      try {
        $userModel = new UserModel();
        if($this->CSRF->validate()){
          if(isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']){
            $arrUsers = $userModel->disableUser($arrQueryStringParams['id']);
            if($arrUsers){
              $arrUsers = $userModel->getUser($arrQueryStringParams['id']);
              if(count($arrUsers) > 0){
                $arrUser = $arrUsers[0];
                $responseData = json_encode($arrUser);
              } else {
                $strErrorDesc = 'Unable to send email to user.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
              }
            } else {
              $strErrorDesc = 'User Not Found.';
              $strErrorHeader = 'HTTP/1.1 404 Unprocessable Entity';
            }
          } else {
            $strErrorDesc = 'User not provided.';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
          }
        } else {
          $strErrorDesc = 'Unable to certify request.';
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

  protected function hex($length = 16){
    return bin2hex(openssl_random_pseudo_bytes($length));
  }
}
