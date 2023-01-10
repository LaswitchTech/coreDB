<?php

//Import BaseController class into the global namespace
use LaswitchTech\phpAPI\BaseController;

//Import Auth class into the global namespace
use LaswitchTech\coreDB\Auth;

class ActivityController extends BaseController {

  public function listAction() {
    $Auth = new Auth();
    $Auth->isAuthorized("activity/list");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if (strtoupper($requestMethod) == 'GET') {
      try {
        if(isset($arrQueryStringParams['id'],$arrQueryStringParams['type']) || isset($arrQueryStringParams['current'])){
          $activityModel = new ActivityModel();
          $limit = 25;
          if(isset($arrQueryStringParams['limit'])){
            $limit = intval($arrQueryStringParams['limit']);
          }
          if(isset($arrQueryStringParams['current'])){
            $owner['users'] = $Auth->getUser('username');
          } else {
            $owner[$arrQueryStringParams['type']] = intval($arrQueryStringParams['id']);
          }
          $arrActivities = $activityModel->getActivities($owner, $limit);
          $responseData = json_encode($arrActivities);
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
