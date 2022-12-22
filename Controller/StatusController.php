<?php

//Import BaseController class into the global namespace
use LaswitchTech\phpAPI\BaseController;

//Import Auth class into the global namespace
use LaswitchTech\coreDB\Auth;

class StatusController extends BaseController {

  public function listAction() {
    $Auth = new Auth(null, null, null, null, 'STRING', 'BOOLEAN');
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if (strtoupper($requestMethod) == 'GET') {
      try {
        $statuses = [
          "auth" => $Auth->isConnected(),
          "user" => $Auth->getStatus(),
          "debug" => false,
          "maintenance" => false,
        ];
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
}
