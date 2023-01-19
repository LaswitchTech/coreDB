<?php

//Import BaseController class into the global namespace
use LaswitchTech\phpAPI\BaseController;

//Import Auth class into the global namespace
use LaswitchTech\coreDB\Auth;

class TopicController extends BaseController {

  public function listAction(){
    $Auth = new Auth();
    $Auth->isAuthorized("topic/list");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if(strtoupper($requestMethod) == 'GET'){
      try {
        $topicModel = new TopicModel();
        $limit = null;
        if(isset($arrQueryStringParams['limit'])){
          $limit = intval($arrQueryStringParams['limit']);
        }
        $owners = [];
        $owners[] = ['users' => $Auth->getUser('username')];
        if($Auth->getUser('organization') != null){
          $owners[] = ['organizations' => $Auth->getUser('organization')];
        }
        if($Auth->getUser('roles') != null){
          $roles = json_decode($Auth->getUser('roles'),true);
          foreach($roles as $role){
            $owners[] = ['roles' => $role];
          }
        }
        $status = 4;
        if(isset($arrQueryStringParams['status'])){
          $status = intval($arrQueryStringParams['status']);
        }
        $arrTopics = $topicModel->getTopicsList($status, $owners, $limit);
        $responseData = json_encode($arrTopics);
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
    $Auth->isAuthorized("topic/get");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if(strtoupper($requestMethod) == 'GET'){
      try {
        $topicModel = new TopicModel();
        if(isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']){
          $owners = [];
          $owners[] = ['users' => $Auth->getUser('username')];
          if($Auth->getUser('organization') != null){
            $owners[] = ['organizations' => $Auth->getUser('organization')];
          }
          if($Auth->getUser('roles') != null){
            $roles = json_decode($Auth->getUser('roles'),true);
            foreach($roles as $role){
              $owners[] = ['roles' => $role];
            }
          }
          $arrTopics = $topicModel->getTopic($arrQueryStringParams['id'],$owners);
          if(count($arrTopics) > 0){
            $responseData = json_encode($arrTopics);
          } else {
            $strErrorDesc = 'Topic Not Found.';
            $strErrorHeader = 'HTTP/1.1 404 Not Found';
          }
        } else {
          $strErrorDesc = 'Topic not provided.';
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

  public function readEmlAction(){
    $Auth = new Auth();
    $Auth->isAuthorized("topic/readEml");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if(strtoupper($requestMethod) == 'GET'){
      try {
        $imapModel = new ImapModel();
        $topicModel = new TopicModel();
        if(isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']){
          $arrEmls = $imapModel->readEml(intval($arrQueryStringParams['id']));
          if($arrEmls > 0){
            $result = [];
            $topics = $topicModel->getTopics(['emls' => intval($arrQueryStringParams['id'])]);
            foreach($topics as $topic){
              $count = count($imapModel->getEmls(['isRead' => 0,'topics' => $topic['id']]));
              if($count != $topic['countUnread']){
                $topic['countUnread'] = count($imapModel->getEmls(['isRead' => 0,'topics' => $topic['id']]));
                if($topic['status'] > 0 && $topic['countUnread'] > 0){ $topic['status'] = 0; }
                if($topic['status'] <= 0 && $topic['countUnread'] <= 0){ $topic['status'] = 1; }
                if($topicModel->updateTopic($topic)){
                  $result[$topic['id']] = $topic;
                }
              }
            }
            $responseData = json_encode($result);
          } else {
            $strErrorDesc = 'Eml Not Found.';
            $strErrorHeader = 'HTTP/1.1 404 Not Found';
          }
        } else {
          $strErrorDesc = 'Eml not provided.';
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
