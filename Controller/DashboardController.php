<?php

//Import BaseController class into the global namespace
use LaswitchTech\phpAPI\BaseController;

//Import Auth class into the global namespace
use LaswitchTech\phpAUTH\Auth;

class DashboardController extends BaseController {

  public function getAction() {
    $Auth = new Auth();
    $Auth->isAuthorized("dashboard/get");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if (strtoupper($requestMethod) == 'GET') {
      if(isset($arrQueryStringParams['id'],$arrQueryStringParams['type']) || isset($arrQueryStringParams['current'])){
        try {
          $dashboardModel = new DashboardModel();
          if(isset($arrQueryStringParams['current'])){
            $owner['users'] = $Auth->getUser('id');
          } else {
            $owner[$arrQueryStringParams['type']] = intval($arrQueryStringParams['id']);
          }
          $arrDashboards = $dashboardModel->getDashboard($owner);
          if(count($arrDashboards) > 0){
            $responseData = json_encode($arrDashboards);
          } else {
            $strErrorDesc = $e->getMessage().'Dashboard Not Found.';
            $strErrorHeader = 'HTTP/1.1 404 Not Found';
          }
        } catch (Error $e) {
          $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
          $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
        }
      } else {
        $strErrorDesc = 'Unable to identify request owner';
        $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
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

  public function saveAction() {
    $Auth = new Auth();
    $Auth->isAuthorized("dashboard/save");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    $arrQueryStringBody = $this->getQueryStringBody();
    if (strtoupper($requestMethod) == 'POST') {
      try {
        if(isset($arrQueryStringParams['id'],$arrQueryStringParams['type']) || isset($arrQueryStringParams['current'])){
          if(isset($arrQueryStringBody['layout'])){
            $dashboardModel = new DashboardModel();
            if(isset($arrQueryStringParams['current'])){
              $owner['users'] = $Auth->getUser('id');
            } else {
              $owner[$arrQueryStringParams['type']] = intval($arrQueryStringParams['id']);
            }
            $layout = json_decode($arrQueryStringBody['layout'], true);
            $arrDashboards = $dashboardModel->saveDashboard($owner,$layout);
            if(count($arrDashboards) > 0){
              $responseData = json_encode($arrDashboards);
            } else {
              $strErrorDesc = $e->getMessage().'Dashboard Not Found.';
              $strErrorHeader = 'HTTP/1.1 404 Not Found';
            }
          } else {
            $strErrorDesc = 'Unable to identify your layout';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
          }
        } else {
          $strErrorDesc = 'Unable to identify request owner';
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
