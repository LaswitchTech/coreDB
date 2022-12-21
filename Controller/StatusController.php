<?php

//Import BaseController class into the global namespace
use LaswitchTech\phpAPI\BaseController;

//Import Auth class into the global namespace
use LaswitchTech\coreDB\Auth;

class StatusController extends BaseController {

  public function listAction() {
    $Auth = new Auth();
    $Auth->isAuthorized("status/list");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if (strtoupper($requestMethod) == 'GET') {
      try {
        $statuses = [
          "auth" => $Auth->isConnected(),
          "user" => 0,
          "debug" => false,
          "maintenance" => false,
        ];
        if($Auth->isConnected() && $Auth->getUser('status')){ $statuses['user'] = $Auth->getUser('status'); }
        if(defined('COREDB_DEBUG')){ $statuses['debug'] = COREDB_DEBUG; }
        if(defined('COREDB_MAINTENANCE')){ $statuses['maintenance'] = COREDB_MAINTENANCE; }
        $responseData = json_encode($statuses);
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
    $Auth->isAuthorized("notification/read");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if (strtoupper($requestMethod) == 'GET') {
      try {
        $notificationModel = new NotificationModel();
        if (isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']) {
          $arrNotifications = $notificationModel->readNotification($arrQueryStringParams['id'], $Auth->getUser('id'));
          if($arrNotifications){
            $responseData = json_encode($arrNotifications);
          } else {
            $strErrorDesc = $e->getMessage().'Notification Not Found.';
            $strErrorHeader = 'HTTP/1.1 404 Not Found';
          }
        } else {
          $strErrorDesc = 'Notification not provided.';
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
