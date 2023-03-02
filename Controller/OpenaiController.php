<?php

//Import BaseController class into the global namespace
use LaswitchTech\phpAPI\BaseController;

//Import Auth class into the global namespace
use LaswitchTech\coreDB\Auth;

//Import phpCSRF Class into the global namespace
use LaswitchTech\phpCSRF\phpCSRF;

//Import phpOpenAI Class into the global namespace
use LaswitchTech\phpOpenAI\phpOpenAI;

//Import Configurator class into the global namespace
use LaswitchTech\coreDB\Configurator;

class OpenaiController extends BaseController {

  protected $Configurator = null;
  protected $CSRF = null;
  protected $OpenAI = null;

  public function __construct(){

    // Initiate Configurator
    $this->Configurator = new Configurator();

    // Initiate phpCSRF
    $this->CSRF = new phpCSRF();

    // Initiate phpOpenAI
    $this->OpenAI = new phpOpenAI();

    // Initiate Parent Constructor
    parent::__construct();
  }

  // public function createAction() {
  //   $Auth = new Auth();
  //   $Auth->isAuthorized("post/create");
  //   $strErrorDesc = '';
  //   $requestMethod = $_SERVER["REQUEST_METHOD"];
  //   $arrQueryStringParams = $this->getQueryStringParams();
  //   $arrPostParams = $this->getPostParams();
  //   if(strtoupper($requestMethod) == 'POST'){
  //     try {
  //       if($this->CSRF->validate()){
  //         // $postModel = new PostModel();
  //         // if(isset($arrPostParams['content'])){
  //         //   $arrPostParams['owner'] = ['users' => $Auth->getUser("username")];
  //         //   if($result = $postModel->new($arrPostParams)){
  //         //     $responseData = json_encode($result);
  //         //   } else {
  //         //     $strErrorDesc = 'Something went wrong! Please contact support.';
  //         //     $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
  //         //   }
  //         // } else {
  //         //   $strErrorDesc = 'Post is empty.';
  //         //   $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
  //         // }
  //       } else {
  //         $strErrorDesc = 'Unable to certify request.';
  //         $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
  //       }
  //     } catch (Error $e) {
  //       $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
  //       $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
  //     }
  //   } else {
  //     $strErrorDesc = 'Method not supported';
  //     $strErrorHeader = 'HTTP/1.1 405 Method Not Allowed';
  //   }
  //   if (!$strErrorDesc) {
  //     $this->output(
  //       $responseData,
  //       array('Content-Type: application/json', 'HTTP/1.1 200 OK')
  //     );
  //   } else {
  //     $this->output(json_encode(array('error' => $strErrorDesc)),
  //       array('Content-Type: application/json', $strErrorHeader)
  //     );
  //   }
  // }
}
