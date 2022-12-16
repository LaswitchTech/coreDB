<?php

//Import BaseController class into the global namespace
use LaswitchTech\phpAPI\BaseController;

//Import Auth class into the global namespace
use LaswitchTech\phpAUTH\Auth;

class RoleController extends BaseController {

  public function listAction(){
    $Auth = new Auth();
    $Auth->isAuthorized("role/list");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if(strtoupper($requestMethod) == 'GET'){
      try {
        $roleModel = new RoleModel();
        $limit = 25;
        if(isset($arrQueryStringParams['limit'])){
          $limit = intval($arrQueryStringParams['limit']);
        }
        $arrRoles = $roleModel->getRoles($limit);
        $responseData = json_encode($arrRoles);
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

  public function getAction(){
    $Auth = new Auth();
    $Auth->isAuthorized("role/get");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if(strtoupper($requestMethod) == 'GET'){
      try {
        $roleModel = new RoleModel();
        if(isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']){
          $arrRoles = $roleModel->getRole($arrQueryStringParams['id']);
          if(count($arrRoles) > 0){
            $responseData = json_encode($arrRoles);
          } else {
            $strErrorDesc = 'Role Not Found.';
            $strErrorHeader = 'HTTP/1.1 404 Not Found';
          }
        } else {
          $strErrorDesc = 'Role not provided.';
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

  public function addAction(){
    $Auth = new Auth();
    $Auth->isAuthorized("role/add");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if(strtoupper($requestMethod) == 'GET'){
      try {
        $roleModel = new RoleModel();
        if(isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']){
          $arrRoles = $roleModel->addRole($arrQueryStringParams['id']);
          if($arrRoles){
            $arrRoles = $roleModel->getRole($arrQueryStringParams['id'],false);
            if(count($arrRoles) > 0){
              $responseData = json_encode($arrRoles);
            } else {
              $strErrorDesc = 'Role Not Found.';
              $strErrorHeader = 'HTTP/1.1 404 Not Found';
            }
          } else {
            $strErrorDesc = 'Unable to create this role.';
            $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
          }
        } else {
          $strErrorDesc = 'Role not provided.';
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

  public function editAction(){
    $Auth = new Auth();
    $Auth->isAuthorized("role/edit");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if(strtoupper($requestMethod) == 'GET'){
      try {
        $roleModel = new RoleModel();
        if(isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']){
          $arrRoles = $roleModel->getRole($arrQueryStringParams['id'], false);
          if(count($arrRoles) > 0){
            $arrRole = $arrRoles[0];
            $arrRole['permissions'] = json_decode($arrRole['permissions'],true);
            $arrRole['members'] = json_decode($arrRole['members'],true);
            // $responseData = json_encode($arrRoles);
            if(isset($arrQueryStringParams['type']) && $arrQueryStringParams['type']){
              if(isset($arrQueryStringParams['action']) && $arrQueryStringParams['action']){
                switch($arrQueryStringParams['type']){
                  case"permission":
                    switch($arrQueryStringParams['action']){
                      case"add":
                        if(isset($arrQueryStringParams['name']) && $arrQueryStringParams['name']){
                          if(!isset($arrRole['permissions'][$arrQueryStringParams['name']])){
                            $arrRole['permissions'][$arrQueryStringParams['name']] = 1;
                            $responseData = json_encode($roleModel->saveRole($arrRole));
                          } else {
                            $strErrorDesc = 'Permission name invalid.';
                            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
                          }
                        } else {
                          $strErrorDesc = 'Permission name not provided.';
                          $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
                        }
                        break;
                      case"set":
                        if(isset($arrQueryStringParams['name']) && $arrQueryStringParams['name']){
                          if(isset($arrQueryStringParams['level']) && $arrQueryStringParams['level'] != ''){
                            if(isset($arrRole['permissions'][$arrQueryStringParams['name']])){
                              $arrRole['permissions'][$arrQueryStringParams['name']] = intval($arrQueryStringParams['level']);
                              $responseData = json_encode($roleModel->saveRole($arrRole));
                            } else {
                              $strErrorDesc = 'Permission name invalid.';
                              $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
                            }
                          } else {
                            $strErrorDesc = 'Level not provided.';
                            $strErrorDesc .= json_encode($arrQueryStringParams);
                            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
                          }
                        } else {
                          $strErrorDesc = 'Permission name not provided.';
                          $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
                        }
                        break;
                      case"remove":
                        if(isset($arrQueryStringParams['name']) && $arrQueryStringParams['name']){
                          if(isset($arrRole['permissions'][$arrQueryStringParams['name']])){
                            unset($arrRole['permissions'][$arrQueryStringParams['name']]);
                            $responseData = json_encode($roleModel->saveRole($arrRole));
                          } else {
                            $strErrorDesc = 'Permission name invalid.';
                            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
                          }
                        } else {
                          $strErrorDesc = 'Permission name not provided.';
                          $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
                        }
                        break;
                      default:
                        $strErrorDesc = 'Unknown request action: '.$arrQueryStringParams['action'].'.';
                        $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
                        break;
                    }
                    break;
                  case"member":
                    switch($arrQueryStringParams['action']){
                      case"add":
                        if(isset($arrQueryStringParams['name']) && $arrQueryStringParams['name']){
                          if(!in_array(["users" => intval($arrQueryStringParams['name'])],$arrRole['members'])){
                            array_push($arrRole['members'],["users" => intval($arrQueryStringParams['name'])]);
                            $responseData = json_encode($roleModel->saveRole($arrRole));
                          } else {
                            $strErrorDesc = 'Member ID invalid.';
                            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
                          }
                        } else {
                          $strErrorDesc = 'Member ID not provided.';
                          $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
                        }
                        break;
                      case"remove":
                        if(isset($arrQueryStringParams['name']) && $arrQueryStringParams['name']){
                          if(in_array(["users" => intval($arrQueryStringParams['name'])],$arrRole['members'])){
                            foreach($arrRole['members'] as $key => $member){
                              if($member === ["users" => intval($arrQueryStringParams['name'])]){
                                unset($arrRole['members'][$key]);
                              }
                            }
                            $responseData = json_encode($roleModel->saveRole($arrRole));
                          } else {
                            $strErrorDesc = 'Member ID invalid.';
                            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
                          }
                        } else {
                          $strErrorDesc = 'Member ID not provided.';
                          $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
                        }
                        break;
                      default:
                        $strErrorDesc = 'Unknown request action: '.$arrQueryStringParams['action'].'.';
                        $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
                        break;
                    }
                    break;
                  default:
                    $strErrorDesc = 'Unknown request type: '.$arrQueryStringParams['type'].'.';
                    $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
                    break;
                }
              } else {
                $strErrorDesc = 'Action not provided.';
                $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
              }
            } else {
              $strErrorDesc = 'Type not provided.';
              $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }
          } else {
            $strErrorDesc = 'Role Not Found.';
            $strErrorHeader = 'HTTP/1.1 404 Not Found';
          }
        } else {
          $strErrorDesc = 'Role not provided.';
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
    $Auth->isAuthorized("role/delete");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if(strtoupper($requestMethod) == 'GET'){
      try {
        $roleModel = new RoleModel();
        if(isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']){
          $arrRoles = $roleModel->deleteRole($arrQueryStringParams['id']);
          if($arrRoles){
            $responseData = json_encode($arrRoles);
          } else {
            $strErrorDesc = 'Role Not Found.';
            $strErrorHeader = 'HTTP/1.1 404 Not Found';
          }
        } else {
          $strErrorDesc = 'Role not provided.';
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
}
