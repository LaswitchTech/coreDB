<?php

//Import BaseController class into the global namespace
use LaswitchTech\phpAPI\BaseController;

//Import Auth class into the global namespace
use LaswitchTech\coreDB\Auth;

//Import phpCSRF Class into the global namespace
use LaswitchTech\phpCSRF\phpCSRF;

//Import Configurator class into the global namespace
use LaswitchTech\coreDB\Configurator;

class PostController extends BaseController {

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
    $Auth->isAuthorized("post/create");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    $arrPostParams = $this->getPostParams();
    if(strtoupper($requestMethod) == 'POST'){
      try {
        if($this->CSRF->validate()){
          $postModel = new PostModel();
          if(isset($arrPostParams['content'])){
            $arrPostParams['owner'] = ['users' => $Auth->getUser("username")];
            if($result = $postModel->new($arrPostParams)){
              $responseData = json_encode($result);
            } else {
              $strErrorDesc = 'Something went wrong! Please contact support.';
              $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
          } else {
            $strErrorDesc = 'Post is empty.';
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
    $Auth->isAuthorized("post/read");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    $arrPostParams = $this->getPostParams();
    if(strtoupper($requestMethod) == 'POST'){
      try {
        $postModel = new PostModel();
        if(isset($arrPostParams['id']) || isset($arrPostParams['linkTo'])){
          if($result = $postModel->get($arrPostParams)){
            $responseData = json_encode($result);
          } else {
            $strErrorDesc = 'Post Not Found.';
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
    $Auth->isAuthorized("post/update");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    $arrPostParams = $this->getPostParams();
    if(strtoupper($requestMethod) == 'POST'){
      try {
        if($this->CSRF->validate()){
          $postModel = new PostModel();
          if(isset($arrPostParams['id'])){
            if(isset($arrPostParams['content'])){
              if($result = $postModel->get($arrPostParams)){
                $result = $result[0];
                $result['content'] = $arrPostParams['content'];
                if($result = $postModel->save($result)){
                  $responseData = json_encode($result);
                } else {
                  $strErrorDesc = 'Something went wrong! Please contact support.';
                  $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                }
              } else {
                $strErrorDesc = 'Post Not Found.';
                $strErrorHeader = 'HTTP/1.1 404 Not Found';
              }
            } else {
              $strErrorDesc = 'Post is empty.';
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
    $Auth->isAuthorized("post/delete");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    $arrPostParams = $this->getPostParams();
    if(strtoupper($requestMethod) == 'POST'){
      try {
        if($this->CSRF->validate()){
          $postModel = new PostModel();
          if(isset($arrPostParams['id'])){
            if($result = $postModel->get($arrPostParams)){
              $result = $result[0];
              if($status = $postModel->remove($result)){
                $responseData = json_encode($result);
              } else {
                $strErrorDesc = 'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
              }
            } else {
              $strErrorDesc = 'Post Not Found.';
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
    $Auth->isAuthorized("post/like");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    $arrPostParams = $this->getPostParams();
    if(strtoupper($requestMethod) == 'POST'){
      try {
        if($this->CSRF->validate()){
          $postModel = new PostModel();
          if(isset($arrPostParams['id'])){
            if($result = $postModel->get($arrPostParams)){
              if($result = $postModel->like($arrPostParams['id'],$Auth->getUser('username'))){
                $responseData = json_encode($result);
              } else {
                $strErrorDesc = 'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
              }
            } else {
              $strErrorDesc = 'Post Not Found.';
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
