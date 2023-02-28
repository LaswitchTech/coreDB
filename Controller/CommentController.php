<?php

//Import BaseController class into the global namespace
use LaswitchTech\phpAPI\BaseController;

//Import Auth class into the global namespace
use LaswitchTech\coreDB\Auth;

//Import phpCSRF Class into the global namespace
use LaswitchTech\phpCSRF\phpCSRF;

//Import Configurator class into the global namespace
use LaswitchTech\coreDB\Configurator;

class CommentController extends BaseController {

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

  public function createAction() {
    $Auth = new Auth();
    $Auth->isAuthorized("comment/create");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    $arrPostParams = $this->getPostParams();
    if(strtoupper($requestMethod) == 'POST'){
      try {
        if($this->CSRF->validate()){
          $commentModel = new CommentModel();
          if(isset($arrPostParams['content'])){
            $arrPostParams['owner'] = ['users' => $Auth->getUser("username")];
            if($result = $commentModel->new($arrPostParams)){
              $responseData = json_encode($result);
            } else {
              $strErrorDesc = 'Something went wrong! Please contact support.';
              $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
          } else {
            $strErrorDesc = 'Comment is empty.';
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

  public function readAction() {
    $Auth = new Auth();
    $Auth->isAuthorized("comment/read");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    $arrPostParams = $this->getPostParams();
    if(strtoupper($requestMethod) == 'POST'){
      try {
        $commentModel = new CommentModel();
        if(isset($arrPostParams['id']) || isset($arrPostParams['linkTo'])){
          if($result = $commentModel->get($arrPostParams)){
            $responseData = json_encode($result);
          } else {
            $strErrorDesc = 'Comment Not Found.';
            $strErrorHeader = 'HTTP/1.1 404 Not Found';
          }
        } else {
          $strErrorDesc = 'Unable to identify the requested object.';
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

  public function updateAction() {
    $Auth = new Auth();
    $Auth->isAuthorized("comment/update");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    $arrPostParams = $this->getPostParams();
    if(strtoupper($requestMethod) == 'POST'){
      try {
        if($this->CSRF->validate()){
          $commentModel = new CommentModel();
          if(isset($arrPostParams['id'])){
            if(isset($arrPostParams['content'])){
              if($result = $commentModel->get($arrPostParams)){
                $result = $result[0];
                $result['content'] = $arrPostParams['content'];
                if($result = $commentModel->save($result)){
                  $responseData = json_encode($result);
                } else {
                  $strErrorDesc = 'Something went wrong! Please contact support.';
                  $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                }
              } else {
                $strErrorDesc = 'Comment Not Found.';
                $strErrorHeader = 'HTTP/1.1 404 Not Found';
              }
            } else {
              $strErrorDesc = 'Comment is empty.';
              $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }
          } else {
            $strErrorDesc = 'Unable to identify the requested object.';
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

  public function deleteAction() {
    $Auth = new Auth();
    $Auth->isAuthorized("comment/delete");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    $arrPostParams = $this->getPostParams();
    if(strtoupper($requestMethod) == 'POST'){
      try {
        if($this->CSRF->validate()){
          $commentModel = new CommentModel();
          if(isset($arrPostParams['id'])){
            if($result = $commentModel->get($arrPostParams)){
              $result = $result[0];
              if($status = $commentModel->remove($result)){
                $responseData = json_encode($result);
              } else {
                $strErrorDesc = 'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
              }
            } else {
              $strErrorDesc = 'Comment Not Found.';
              $strErrorHeader = 'HTTP/1.1 404 Not Found';
            }
          } else {
            $strErrorDesc = 'Unable to identify the requested object.';
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

  public function likeAction() {
    $Auth = new Auth();
    $Auth->isAuthorized("comment/like");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    $arrPostParams = $this->getPostParams();
    if(strtoupper($requestMethod) == 'POST'){
      try {
        if($this->CSRF->validate()){
          $commentModel = new CommentModel();
          if(isset($arrPostParams['id'])){
            if($result = $commentModel->get($arrPostParams)){
              if($result = $commentModel->like($arrPostParams['id'],$Auth->getUser('username'))){
                $responseData = json_encode($result);
              } else {
                $strErrorDesc = 'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
              }
            } else {
              $strErrorDesc = 'Comment Not Found.';
              $strErrorHeader = 'HTTP/1.1 404 Not Found';
            }
          } else {
            $strErrorDesc = 'Unable to identify the requested object.';
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
}
