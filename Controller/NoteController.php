<?php

//Import BaseController class into the global namespace
use LaswitchTech\phpAPI\BaseController;

//Import Auth class into the global namespace
use LaswitchTech\coreDB\Auth;

//Import phpCSRF Class into the global namespace
use LaswitchTech\phpCSRF\phpCSRF;

//Import Configurator class into the global namespace
use LaswitchTech\coreDB\Configurator;

class NoteController extends BaseController {

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
    $Auth->isAuthorized("note/create");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    $arrPostParams = $this->getPostParams();
    if(strtoupper($requestMethod) == 'POST'){
      try {
        if($this->CSRF->validate()){
          $noteModel = new NoteModel();
          if(isset($arrPostParams['content'])){
            $arrPostParams['owner'] = ['users' => $Auth->getUser("username")];
            $arrPostParams['sharedTo'] = [];
            $arrPostParams['sharedTo'][] = ['users' => strval($Auth->getUser('username'))];
            if($Auth->getUser('organization') != null){
              $arrPostParams['sharedTo'][] = ['organizations' => strval($Auth->getUser('organization'))];
            }
            if($result = $noteModel->new($arrPostParams)){
              $responseData = json_encode($result);
            } else {
              $strErrorDesc = 'Something went wrong! Please contact support.';
              $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
          } else {
            $strErrorDesc = 'Note is empty.';
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
    $Auth->isAuthorized("note/read");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    $arrPostParams = $this->getPostParams();
    if(strtoupper($requestMethod) == 'POST'){
      try {
        $noteModel = new NoteModel();
        if(isset($arrPostParams['id']) || isset($arrPostParams['linkTo'])){
          $arrPostParams['sharedTo'] = [];
          $arrPostParams['sharedTo'][] = ['users' => strval($Auth->getUser('username'))];
          if($Auth->getUser('organization') != null){
            $arrPostParams['sharedTo'][] = ['organizations' => strval($Auth->getUser('organization'))];
          }
          if($result = $noteModel->get($arrPostParams)){
            $responseData = json_encode($result);
          } else {
            $strErrorDesc = 'Note Not Found.';
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
    $Auth->isAuthorized("note/update");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    $arrPostParams = $this->getPostParams();
    if(strtoupper($requestMethod) == 'POST'){
      try {
        if($this->CSRF->validate()){
          $noteModel = new NoteModel();
          if(isset($arrPostParams['id']) || isset($arrPostParams['linkTo'])){
            if(isset($arrPostParams['content'])){
              $arrPostParams['sharedTo'] = [];
              $arrPostParams['sharedTo'][] = ['users' => strval($Auth->getUser('username'))];
              if($Auth->getUser('organization') != null){
                $arrPostParams['sharedTo'][] = ['organizations' => strval($Auth->getUser('organization'))];
              }
              if($result = $noteModel->get($arrPostParams)){
                foreach($arrPostParams as $key => $value){
                  if(isset($result[$key])){
                    $result[$key] = $value;
                  }
                }
                $result['owner'] = ['users' => $Auth->getUser("username")];
                if($result = $noteModel->edit($result)){
                  $responseData = json_encode($result);
                } else {
                  $strErrorDesc = 'Something went wrong! Please contact support.';
                  $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                }
              } else {
                $strErrorDesc = 'Note Not Found.';
                $strErrorHeader = 'HTTP/1.1 404 Not Found';
              }
            } else {
              $strErrorDesc = 'Note is empty.';
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
    $Auth->isAuthorized("note/delete");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    $arrPostParams = $this->getPostParams();
    if(strtoupper($requestMethod) == 'POST'){
      try {
        if($this->CSRF->validate()){
          $noteModel = new NoteModel();
          if(isset($arrPostParams['id']) || isset($arrPostParams['linkTo'])){
            $arrPostParams['sharedTo'] = [];
            $arrPostParams['sharedTo'][] = ['users' => strval($Auth->getUser('username'))];
            if($Auth->getUser('organization') != null){
              $arrPostParams['sharedTo'][] = ['organizations' => strval($Auth->getUser('organization'))];
            }
            if($result = $noteModel->get($arrPostParams)){
              if($status = $noteModel->remove($result)){
                $responseData = json_encode($result);
              } else {
                $strErrorDesc = 'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
              }
            } else {
              $strErrorDesc = 'Note Not Found.';
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
