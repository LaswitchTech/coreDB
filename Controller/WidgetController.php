<?php

//Import BaseController class into the global namespace
use LaswitchTech\phpAPI\BaseController;

//Import Auth class into the global namespace
use LaswitchTech\phpAUTH\Auth;

class WidgetController extends BaseController {

  public function listAction() {
    $Auth = new Auth();
    $Auth->isAuthorized("widget/list");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if (strtoupper($requestMethod) == 'GET') {
      try {
        $widgetModel = new WidgetModel();
        $limit = 25;
        if(isset($arrQueryStringParams['limit'])){
          $limit = intval($arrQueryStringParams['limit']);
        }
        $arrWidgets = $widgetModel->getWidgets($limit);
        $responseData = json_encode($arrWidgets);
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

  public function getAction() {
    $Auth = new Auth();
    $Auth->isAuthorized("widget/get");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if (strtoupper($requestMethod) == 'GET') {
      try {
        if(isset($arrQueryStringParams['id'])){
          $widgetModel = new WidgetModel();
          $arrWidgets = $widgetModel->getWidget($arrQueryStringParams['id']);
          if(count($arrWidgets) > 0){
            $responseData = json_encode($arrWidgets);
          } else {
            $strErrorDesc = 'Widget Not Found.';
            $strErrorHeader = 'HTTP/1.1 404 Not Found';
          }
          $responseData = json_encode($arrWidgets);
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
