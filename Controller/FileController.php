<?php

//Import BaseController class into the global namespace
use LaswitchTech\phpAPI\BaseController;

//Import Auth class into the global namespace
use LaswitchTech\coreDB\Auth;

//Import phpCSRF Class into the global namespace
use LaswitchTech\phpCSRF\phpCSRF;

//Import Configurator class into the global namespace
use LaswitchTech\coreDB\Configurator;

class FileController extends BaseController {

  protected $Configurator = null;
  protected $CSRF = null;

  public function __construct(){

    // Initiate Configurator
    $this->Configurator = new Configurator();

    // Initiate phpCSRF
    $this->CSRF = new phpCSRF();

    // Initiate Parent Constructor
    parent::__construct();

    // if($this->CSRF->validate()){
    // } else {
    //   $strErrorDesc = 'Unable to certify request.';
    //   $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
    // }
  }

  public function uploadAction(){
    $Auth = new Auth();
    $Auth->isAuthorized("file/upload");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    $arrPostParams = $this->getPostParams();
    if (strtoupper($requestMethod) == 'POST') {
      try {
        $fileModel = new FileModel();
        if($this->CSRF->validate()){
          if(isset($arrPostParams['name'],$arrPostParams['content'],$arrPostParams['type'],$arrPostParams['size'])){
            $file = [
              'filename' => $arrPostParams['name'],
              'name' => $arrPostParams['name'],
              'type' => $arrPostParams['type'],
              'content' => $arrPostParams['content'],
              'size' => intval($arrPostParams['size']),
            ];
            $file['type'] = explode('/',$file['type']);
            $file['type'] = end($file['type']);
            if(isset($arrPostParams['sharedTo'])){
              if($arrPostParams['sharedTo'] == null){
                $file['sharedTo'] = [];
              } else {
                if(is_string($arrPostParams['sharedTo'])){
                  $file['sharedTo'] = json_decode($arrPostParams['sharedTo'],true);
                } else {
                  $file['sharedTo'] = $arrPostParams['sharedTo'];
                }
              }
            }
            if(isset($arrPostParams['meta'])){
              if($arrPostParams['meta'] == null){
                $file['meta'] = [];
              } else {
                if(is_string($arrPostParams['meta'])){
                  $file['meta'] = json_decode($arrPostParams['meta'],true);
                } else {
                  $file['meta'] = $arrPostParams['meta'];
                }
              }
            }
            if(isset($arrPostParams['dataset'])){
              if($arrPostParams['dataset'] == null){
                $file['dataset'] = [];
              } else {
                if(is_string($arrPostParams['dataset'])){
                  $file['dataset'] = json_decode($arrPostParams['dataset'],true);
                } else {
                  $file['dataset'] = $arrPostParams['dataset'];
                }
              }
            }
            if(isset($arrPostParams['isPublic'])){
              $file['isPublic'] = intval($arrPostParams['isPublic']);
            }
            if(isset($arrPostParams['isDeleted'])){
              $file['isDeleted'] = intval($arrPostParams['isDeleted']);
            }
            if(isset($arrPostParams['created'])){
              $file['created'] = date('Y-m-d H:i:s',intval($arrPostParams['created']));
            }
            if(isset($file['content'])){
              if(strpos($file['content'], 'data:') !== false){
                $file['content'] = trim(str_replace('data:' . $arrPostParams['type'] . ';','',$file['content']));
              }
              if(strpos($file['content'], 'base64,') !== false){
                $file['content'] = trim(str_replace('base64,','',$file['content']));
              }
              if(strpos($file['content'], ',') !== false){
                $file['content'] = explode(',',$file['content']);
                $file['content'] = end($file['content']);
                $file['content'] = trim($file['content']);
              }
              $file['content'] = base64_decode($file['content']);
              $file['encoding'] = 3;
              $file['path'] = 'controller/file/upload/';
            }
            $result = $fileModel->uploadFile($file);
            if($result){
              $responseData = json_encode($result);
            } else {
              $strErrorDesc = 'Unable to unpublish this file.';
              $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
          } else {
            $strErrorDesc = 'Critical file metadata missing.';
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

  public function unpublishAction(){
    $Auth = new Auth();
    $Auth->isAuthorized("file/unpublish");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if (strtoupper($requestMethod) == 'GET') {
      try {
        $fileModel = new FileModel();
        if($this->CSRF->validate()){
          if(isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']) {
            $id = intval($arrQueryStringParams['id']);
            if($arrFile = $fileModel->getFile($id)){
              unset($arrFile['content']);
              $ACL = false;
              if(!$ACL){
                if($arrFile['isPublic'] > 0){ $ACL = true; }
              }
              if(!$ACL){
                if($Auth->getUser('roles') != null){
                  $roles = json_decode($Auth->getUser('roles'),true);
                  foreach($roles as $role){
                    if(in_array($role,$arrFile['sharedTo'])){ $ACL = true; }
                    if($ACL){ break; }
                  }
                }
              }
              if(!$ACL){
                if($Auth->getUser('organization') != null){
                  $organization = ['organizations' => $Auth->getUser('organization')];
                  if(in_array($organization,$arrFile['sharedTo'])){ $ACL = true; }
                }
              }
              if(!$ACL){
                $user = ['users' => $Auth->getUser('username')];
                if(in_array($user,$arrFile['sharedTo'])){ $ACL = true; }
              }
              if($ACL){
                if($fileModel->unpublishFile($id)){
                  $arrFile['isPublic'] = 0;
                  $responseData = json_encode($arrFile);
                } else {
                  $strErrorDesc = 'Unable to unpublish this file.';
                  $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                }
              } else {
                $strErrorDesc = 'Permission Denied.';
                $strErrorHeader = 'HTTP/1.1 403 Permission Denied';
              }
            } else {
              $strErrorDesc = 'File Not Found.';
              $strErrorHeader = 'HTTP/1.1 404 Not Found';
            }
          } else {
            $strErrorDesc = 'File not provided.';
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

  public function publishAction(){
    $Auth = new Auth();
    $Auth->isAuthorized("file/publish");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if (strtoupper($requestMethod) == 'GET') {
      try {
        $fileModel = new FileModel();
        if($this->CSRF->validate()){
          if(isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']) {
            $id = intval($arrQueryStringParams['id']);
            if($arrFile = $fileModel->getFile($id)){
              unset($arrFile['content']);
              $ACL = false;
              if(!$ACL){
                if($arrFile['isPublic'] > 0){ $ACL = true; }
              }
              if(!$ACL){
                if($Auth->getUser('roles') != null){
                  $roles = json_decode($Auth->getUser('roles'),true);
                  foreach($roles as $role){
                    if(in_array($role,$arrFile['sharedTo'])){ $ACL = true; }
                    if($ACL){ break; }
                  }
                }
              }
              if(!$ACL){
                if($Auth->getUser('organization') != null){
                  $organization = ['organizations' => $Auth->getUser('organization')];
                  if(in_array($organization,$arrFile['sharedTo'])){ $ACL = true; }
                }
              }
              if(!$ACL){
                $user = ['users' => $Auth->getUser('username')];
                if(in_array($user,$arrFile['sharedTo'])){ $ACL = true; }
              }
              if($ACL){
                if($fileModel->publishFile($id)){
                  $arrFile['isPublic'] = 1;
                  $responseData = json_encode($arrFile);
                } else {
                  $strErrorDesc = 'Unable to publish this file.';
                  $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                }
              } else {
                $strErrorDesc = 'Permission Denied.';
                $strErrorHeader = 'HTTP/1.1 403 Permission Denied';
              }
            } else {
              $strErrorDesc = 'File Not Found.';
              $strErrorHeader = 'HTTP/1.1 404 Not Found';
            }
          } else {
            $strErrorDesc = 'File not provided.';
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

  public function restoreAction(){
    $Auth = new Auth();
    $Auth->isAuthorized("file/restore");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if (strtoupper($requestMethod) == 'GET') {
      try {
        $fileModel = new FileModel();
        if($this->CSRF->validate()){
          if(isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']) {
            $id = intval($arrQueryStringParams['id']);
            if($arrFile = $fileModel->getFile($id)){
              unset($arrFile['content']);
              $ACL = false;
              if(!$ACL){
                if($arrFile['isPublic'] > 0){ $ACL = true; }
              }
              if(!$ACL){
                if($Auth->getUser('roles') != null){
                  $roles = json_decode($Auth->getUser('roles'),true);
                  foreach($roles as $role){
                    if(in_array($role,$arrFile['sharedTo'])){ $ACL = true; }
                    if($ACL){ break; }
                  }
                }
              }
              if(!$ACL){
                if($Auth->getUser('organization') != null){
                  $organization = ['organizations' => $Auth->getUser('organization')];
                  if(in_array($organization,$arrFile['sharedTo'])){ $ACL = true; }
                }
              }
              if(!$ACL){
                $user = ['users' => $Auth->getUser('username')];
                if(in_array($user,$arrFile['sharedTo'])){ $ACL = true; }
              }
              if($ACL){
                if($fileModel->restoreFile($id)){
                  $arrFile['isDeleted'] = 0;
                  $responseData = json_encode($arrFile);
                } else {
                  $strErrorDesc = 'Unable to restore this file.';
                  $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                }
              } else {
                $strErrorDesc = 'Permission Denied.';
                $strErrorHeader = 'HTTP/1.1 403 Permission Denied';
              }
            } else {
              $strErrorDesc = 'File Not Found.';
              $strErrorHeader = 'HTTP/1.1 404 Not Found';
            }
          } else {
            $strErrorDesc = 'File not provided.';
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

  public function deleteAction(){
    $Auth = new Auth();
    $Auth->isAuthorized("file/delete");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if (strtoupper($requestMethod) == 'GET') {
      try {
        $fileModel = new FileModel();
        if($this->CSRF->validate()){
          if(isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']) {
            $id = intval($arrQueryStringParams['id']);
            if($arrFile = $fileModel->getFile($id)){
              unset($arrFile['content']);
              $ACL = false;
              if(!$ACL){
                if($arrFile['isPublic'] > 0){ $ACL = true; }
              }
              if(!$ACL){
                if($Auth->getUser('roles') != null){
                  $roles = json_decode($Auth->getUser('roles'),true);
                  foreach($roles as $role){
                    if(in_array($role,$arrFile['sharedTo'])){ $ACL = true; }
                    if($ACL){ break; }
                  }
                }
              }
              if(!$ACL){
                if($Auth->getUser('organization') != null){
                  $organization = ['organizations' => $Auth->getUser('organization')];
                  if(in_array($organization,$arrFile['sharedTo'])){ $ACL = true; }
                }
              }
              if(!$ACL){
                $user = ['users' => $Auth->getUser('username')];
                if(in_array($user,$arrFile['sharedTo'])){ $ACL = true; }
              }
              if($ACL){
                if($fileModel->deleteFile($id)){
                  $arrFile['isDeleted'] = 1;
                  $responseData = json_encode($arrFile);
                } else {
                  $strErrorDesc = 'Unable to delete this file.';
                  $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                }
              } else {
                $strErrorDesc = 'Permission Denied.';
                $strErrorHeader = 'HTTP/1.1 403 Permission Denied';
              }
            } else {
              $strErrorDesc = 'File Not Found.';
              $strErrorHeader = 'HTTP/1.1 404 Not Found';
            }
          } else {
            $strErrorDesc = 'File not provided.';
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

  public function downloadAction(){
    $Auth = new Auth();
    $Auth->isAuthorized("file/download");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if (strtoupper($requestMethod) == 'GET') {
      try {
        $fileModel = new FileModel();
        if($this->CSRF->validate()){
          if(isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']) {
            if($arrFile = $fileModel->getFile(intval($arrQueryStringParams['id']))){
              $arrFile['content'] = base64_encode($arrFile['content']);
              $ACL = false;
              if(!$ACL){
                if($arrFile['isPublic'] > 0){ $ACL = true; }
              }
              if(!$ACL){
                if($Auth->getUser('roles') != null){
                  $roles = json_decode($Auth->getUser('roles'),true);
                  foreach($roles as $role){
                    if(in_array($role,$arrFile['sharedTo'])){ $ACL = true; }
                    if($ACL){ break; }
                  }
                }
              }
              if(!$ACL){
                if($Auth->getUser('organization') != null){
                  $organization = ['organizations' => $Auth->getUser('organization')];
                  if(in_array($organization,$arrFile['sharedTo'])){ $ACL = true; }
                }
              }
              if(!$ACL){
                $user = ['users' => $Auth->getUser('username')];
                if(in_array($user,$arrFile['sharedTo'])){ $ACL = true; }
              }
              if($ACL){
                if($arrFile['isDeleted'] < 1){
                  $responseData = json_encode($arrFile);
                } else {
                  $strErrorDesc = 'This file was deleted.';
                  $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
                }
              } else {
                $strErrorDesc = 'Permission Denied.';
                $strErrorHeader = 'HTTP/1.1 403 Permission Denied';
              }
            } else {
              $strErrorDesc = 'File Not Found.';
              $strErrorHeader = 'HTTP/1.1 404 Not Found';
            }
          } else {
            $strErrorDesc = 'File not provided.';
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
