<?php

//Import BaseController class into the global namespace
use LaswitchTech\phpAPI\BaseController;

//Import Auth class into the global namespace
use LaswitchTech\coreDB\Auth;

//Import phpCSRF Class into the global namespace
use LaswitchTech\phpCSRF\phpCSRF;

//Import Configurator class into the global namespace
use LaswitchTech\coreDB\Configurator;

class TopicController extends BaseController {

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

  public function addContactAction(){
    $Auth = new Auth();
    $Auth->isAuthorized("topic/addContact");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if(strtoupper($requestMethod) == 'GET'){
      try {
        $topicModel = new TopicModel();
        if($this->CSRF->validate()){
          if(isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']){
            if(isset($arrQueryStringParams['contact']) && $arrQueryStringParams['contact']){
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
              $arrTopics = $topicModel->getTopic($arrQueryStringParams['id'],$owners,false);
              if(count($arrTopics) > 0){
                $arrTopic = $arrTopics[0];
                if(filter_var($arrQueryStringParams['contact'], FILTER_VALIDATE_EMAIL)){
                  if(!in_array(strval($arrQueryStringParams['contact']),$arrTopic['contacts'])){
                    $arrTopic['contacts'][] = $arrQueryStringParams['contact'];
                    if($topicModel->updateTopic($arrTopic)){
                      $responseData = json_encode($arrTopic);
                    } else {
                      $strErrorDesc = 'Unable to update contact listing of topic.';
                      $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                    }
                  } else {
                    $strErrorDesc = 'This contact already exist.';
                    $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
                  }
                } else {
                  $strErrorDesc = 'Invalid email address.';
                  $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
                }
              } else {
                $strErrorDesc = 'Topic Not Found.';
                $strErrorHeader = 'HTTP/1.1 404 Not Found';
              }
            } else {
              $strErrorDesc = 'File not provided.';
              $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }
          } else {
            $strErrorDesc = 'Topic not provided.';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
          }
        } else {
          $strErrorDesc = 'Unable to certify request.';
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

  public function addFileAction(){
    $Auth = new Auth();
    $Auth->isAuthorized("topic/addFile");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if(strtoupper($requestMethod) == 'GET'){
      try {
        $topicModel = new TopicModel();
        if($this->CSRF->validate()){
          if(isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']){
            if(isset($arrQueryStringParams['file']) && $arrQueryStringParams['file']){
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
              $arrTopics = $topicModel->getTopic($arrQueryStringParams['id'],$owners,false);
              if(count($arrTopics) > 0){
                $arrTopic = $arrTopics[0];
                if(!in_array(strval($arrQueryStringParams['file']),$arrTopic['files'])){
                  $arrTopic['files'][] = $arrQueryStringParams['file'];
                  if($topicModel->updateTopic($arrTopic)){
                    $responseData = json_encode($arrTopic);
                  } else {
                    $strErrorDesc = 'Unable to update file listing of topic.';
                    $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                  }
                } else {
                  $strErrorDesc = 'This file already exist.';
                  $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
                }
              } else {
                $strErrorDesc = 'Topic Not Found.';
                $strErrorHeader = 'HTTP/1.1 404 Not Found';
              }
            } else {
              $strErrorDesc = 'File not provided.';
              $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }
          } else {
            $strErrorDesc = 'Topic not provided.';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
          }
        } else {
          $strErrorDesc = 'Unable to certify request.';
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

  public function noteAction() {
    $Auth = new Auth();
    $Auth->isAuthorized("topic/note");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    $arrPostParams = $this->getPostParams();
    if(strtoupper($requestMethod) == 'POST'){
      try {
        $topicModel = new TopicModel();
        if(isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']){
          $id = $arrQueryStringParams['id'];
          $owner = ['users' => $Auth->getUser('username')];
          $owners = [];
          $owners[] = ['users' => $Auth->getUser('username')];
          if($Auth->getUser('organization') != null){
            $owners[] = ['organizations' => $Auth->getUser('organization')];
          }
          $sharedTo = $owners;
          if($Auth->getUser('roles') != null){
            $roles = json_decode($Auth->getUser('roles'),true);
            foreach($roles as $role){
              $owners[] = ['roles' => $role];
            }
          }
          $arrTopics = $topicModel->getTopic($id,$owners);
          if(count($arrTopics) > 0){
            $arrTopic = $arrTopics[0];
            $topic = $arrTopic['id'];
            if(isset($arrPostParams['content']) && $arrPostParams['content']){
              $content = strval($arrPostParams['content']);
              $content = base64_decode($content);
              $linkTo = null;
              if(isset($arrPostParams['linkTo']) && $arrPostParams['linkTo']){
                $arrPostParams['linkTo'] = json_decode($arrPostParams['linkTo'],true);
                $linkTo = [$arrPostParams['linkTo']];
              }
              $note = [
                'topic' => $topic,
                'content' => $content,
                'owner' => $owner,
                'linkTo' => $linkTo,
                'sharedTo' => $sharedTo,
              ];
              if($resultTopicComment = $topicModel->addNote($note)){
                if(isset($resultTopicComment['owner'])){ $resultTopicComment['owner'] = json_decode($resultTopicComment['owner'],true); }
                if(isset($resultTopicComment['sharedTo'])){ $resultTopicComment['sharedTo'] = json_decode($resultTopicComment['sharedTo'],true); }
                if(isset($resultTopicComment['linkTo'])){ $resultTopicComment['linkTo'] = json_decode($resultTopicComment['linkTo'],true); }
                // Add Activity
                $activityModel = new ActivityModel();
                $activityModel->addActivity(['users' => $Auth->getUser('username')],[
                  "header" => 'You noted on topic: ' . $topic,
                  "color" => 'primary',
                  "icon" => 'chat-left-text',
                  "route" => '/topics/details?id=' . $topic,
                  "sharedTo" => [["users" => $Auth->getUser('username')]],
                ]);
                // Add Notifications
                $notificationModel = new NotificationModel();
                foreach($sharedTo as $entity){
                  $table = array_key_first($entity);
                  if($table == "users"){
                    $notificationModel->addNotification($entity[$table], $Auth->getUser('username') . " noted on topic: " . $topic, "/topics/details?id=" . $arrTopic['id']);
                  }
                }
                $responseData = json_encode($resultTopicComment);
              } else {
                $strErrorDesc = 'Unable to save comment.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
              }
            } else {
              $strErrorDesc = 'Comment content not provided.';
              $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }
          } else {
            $strErrorDesc = 'Topic Not Found.';
            $strErrorHeader = 'HTTP/1.1 404 Not Found';
          }
        } else {
          $strErrorDesc = 'Topic not provided.';
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

  public function commentAction() {
    $Auth = new Auth();
    $Auth->isAuthorized("topic/comment");
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    $arrPostParams = $this->getPostParams();
    if(strtoupper($requestMethod) == 'POST'){
      try {
        $topicModel = new TopicModel();
        if(isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']){
          $id = $arrQueryStringParams['id'];
          $owner = ['users' => $Auth->getUser('username')];
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
          $arrTopics = $topicModel->getTopic($id,$owners);
          if(count($arrTopics) > 0){
            $arrTopic = $arrTopics[0];
            $topic = $arrTopic['id'];
            if(isset($arrPostParams['content']) && $arrPostParams['content']){
              $content = strval($arrPostParams['content']);
              $content = base64_decode($content);
              $linkTo = null;
              if(isset($arrPostParams['linkTo']) && $arrPostParams['linkTo']){
                $arrPostParams['linkTo'] = json_decode($arrPostParams['linkTo'],true);
                $linkTo = [$arrPostParams['linkTo']];
              }
              $comment = [
                'topic' => $topic,
                'content' => $content,
                'owner' => $owner,
                'linkTo' => $linkTo,
              ];
              if($resultTopicComment = $topicModel->addComment($comment)){
                if(isset($resultTopicComment['owner'])){ $resultTopicComment['owner'] = json_decode($resultTopicComment['owner'],true); }
                if(isset($resultTopicComment['linkTo'])){ $resultTopicComment['linkTo'] = json_decode($resultTopicComment['linkTo'],true); }
                // Add Activity
                $activityModel = new ActivityModel();
                $activityModel->addActivity(['users' => $Auth->getUser('username')],[
                  "header" => 'You commented on topic: ' . $topic,
                  "color" => 'primary',
                  "icon" => 'chat-left-text',
                  "route" => '/topics/details?id=' . $topic,
                  "sharedTo" => [["users" => $Auth->getUser('username')]],
                ]);
                // Add Notifications
                $notificationModel = new NotificationModel();
                foreach($arrTopic['sharedTo'] as $entity){
                  $table = array_key_first($entity);
                  if($table == "users"){
                    $notificationModel->addNotification($entity[$table], $Auth->getUser('username') . " commented on topic: " . $topic, "/topics/details?id=" . $arrTopic['id']);
                  }
                }
                $responseData = json_encode($resultTopicComment);
              } else {
                $strErrorDesc = 'Unable to save comment.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
              }
            } else {
              $strErrorDesc = 'Comment content not provided.';
              $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }
          } else {
            $strErrorDesc = 'Topic Not Found.';
            $strErrorHeader = 'HTTP/1.1 404 Not Found';
          }
        } else {
          $strErrorDesc = 'Topic not provided.';
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
