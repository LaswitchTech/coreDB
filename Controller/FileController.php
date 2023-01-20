<?php

//Import BaseController class into the global namespace
use LaswitchTech\phpAPI\BaseController;

//Import Auth class into the global namespace
use LaswitchTech\coreDB\Auth;

class FileController extends BaseController {

  public function unpublishAction(){
    $Auth = new Auth();
    $Auth->isAuthorized("file/unpublish");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if (strtoupper($requestMethod) == 'GET') {
      try {
        $fileModel = new FileModel();
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
