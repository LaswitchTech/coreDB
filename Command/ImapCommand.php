<?php

//Import BaseCommand class into the global namespace
use LaswitchTech\phpCLI\BaseCommand;

//Import Configurator class into the global namespace
use LaswitchTech\coreDB\Configurator;

//Import phpSMTP class into the global namespace
use LaswitchTech\IMAP\phpIMAP;

class ImapCommand extends BaseCommand {

  protected $Configurator = null;

  public function __construct(){

    // Setup Configurator
    $this->Configurator = new Configurator();

    // Initiate Parent Constructor
    parent::__construct();
  }

  public function addAction($argv){
    if(count($argv) > 0){
      $type = $argv[0];
      switch($type){
        case "account":
          // Setup IMAP Account
          $requestIMAP = function(){
            $conf = [];
            $port = 143;
            $conf['host'] = $this->input('What is the host?', 'localhost');
            $conf['encryption'] = $this->input('What is the security of the server?',['NONE','SSL','STARTTLS'],'SSL');
            $conf['encryption'] = strtoupper($conf['encryption']);
            switch($conf['encryption']){
              case"NONE": $port = 143; break;
              case"SSL":
              case"STARTTLS": $port = 993; break;
            }
            $conf['port'] = intval($this->input('What is the port for the server?',"$port"));
            $conf['username'] = $this->input('What is the username for the server?');
            $conf['password'] = $this->input('What is the password for the server?');
            if($conf['encryption'] != "NONE"){
              $conf['isSelfSigned'] = $this->input('Is the server certificat self-signed?',['Y','N'],'Y');
              $conf['isSelfSigned'] = strtoupper($conf['isSelfSigned']);
              switch($conf['isSelfSigned']){
                case "Y": $conf['isSelfSigned'] = true; break;
                case "N": $conf['isSelfSigned'] = true; break;
              }
            } else {
              $conf['isSelfSigned'] = true;
            }
            return $conf;
          };
          $testIMAP = false;
          do {
            $config = $requestIMAP();
            $this->info("Testing the connection");
            $phpIMAP = new phpIMAP();
            if($phpIMAP->login($config['username'],$config['password'],$config['host'],$config['port'],$config['encryption'],$config['isSelfSigned'])){
              $this->success("Connection established");
              $testIMAP = true;
            } else {
              $this->error("Unable to establish a connection");
            }
          } while (!$testIMAP);
          $imapModel = new ImapModel();
          if($imapModel->addAccount($config)){
            $this->success("Account Saved");
          } else {
            $this->error("Unable to save the account");
          }
          break;
        case "fetcher":
          if(count($argv) > 1){
            $account = $argv[1];
            $folder = 'INBOX';
            if(count($argv) > 2){
              $folder = $argv[2];
            }
            $imapModel = new ImapModel();
            if($imapModel->addFetcher($account,$folder)){
              $this->success($account . " added to fetchers");
            } else {
              $this->error("Unable to add " . $account . " to fetchers");
            }
          } else {
            $this->error('You must specify an IMAP account to add to fetchers');
          }
          break;
        default:
          $this->error('Unknown type of object');
          break;
      }
    } else {
      $this->error('You must provide a type of object to create');
    }
  }

  public function enableAction($argv){
    if(count($argv) > 0){
      $imapModel = new ImapModel();
      foreach($argv as $account){
        if($imapModel->enable($account)){
          $this->success('Fetcher ' . $account . ' was enabled');
        }
      }
    }
  }

  public function disableAction($argv){
    if(count($argv) > 0){
      $imapModel = new ImapModel();
      foreach($argv as $account){
        if($imapModel->disable($account)){
          $this->success('Fetcher ' . $account . ' was disabled');
        }
      }
    }
  }

  public function fetchAction($argv){
    $this->output("Starting Fetcher...");
    $imapModel = new ImapModel();
    foreach($imapModel->getFetchers() as $key => $fetcher){
      $accounts = $imapModel->getAccount($fetcher['account']);
      if(count($accounts) > 0){
        $account = $accounts[0];
        $phpIMAP = new phpIMAP($account['host'],$account['port'],$account['encryption'],$account['username'],$account['password'],$account['isSelfSigned']);
        if($phpIMAP->isConnected()){
          $this->output("Connection to [" . $account['username'] . "] was established");
          $this->output("Retrieving Messages");
          $emls = $phpIMAP->get(['format' => true, 'folder' => $fetcher['folder']]);
          if(is_array($emls->messages) && count($emls->messages) > 0){
            $this->output("Saving Messages");
            foreach($emls->messages as $eml){
              $message = [
                'account' => $account['username'],
                'folder' => $emls->Folder,
                'date' => date('Y-m-d H:i:s',strtotime($eml->Date)),
                'mid' => $eml->message_id,
                'uid' => $eml->UID,
                'sender' => $eml->Sender,
                'from' => $eml->From,
                'to' => $eml->To,
                'cc' => $eml->CC,
                'bcc' => $eml->BCC,
                'meta' => $eml->Meta->References->Formatted,
                'subject' => $eml->Subject->Full,
                'subject_stripped' => $eml->Subject->PLAIN,
                'body' => $eml->Body->Content,
                'body_stripped' => $eml->Body->Unquoted,
                'files' => [],
              ];
              if($eml->in_reply_to != '' && $eml->in_reply_to){ $message['reply_to_id'] = $eml->in_reply_to; }
              if($eml->references != '' && $eml->references){ $message['reference_id'] = $eml->references; }
              $message['meta']['other'] = [];
              foreach($eml->Meta->References->Plain as $key => $value){
                if(strpos($value, ':') === false){
                  $message['meta']['other'][] = $value;
                }
              }
              if(count($eml->Attachments->Files) > 0){
                foreach($eml->Attachments->Files as $key => $value){
                  if($value['is_attachment']){
                    $parts = explode('.',$value['filename']);
                    $file = [
                      'name' => $value['name'],
                      'filename' => $value['filename'],
                      'content' => $value['attachment'],
                      'type' => end($parts),
                      'size' => $value['bytes'],
                      'encoding' => $value['encoding'],
                    ];
                    array_push($message['files'],$file);
                  }
                }
              }
              $this->output("Saving Message[" . $message['uid'] . "]: " . $message['subject_stripped']);
              if($imapModel->saveEml($message)){
                $this->output("Saved");
                $this->output("Deleting Message[" . $message['uid'] . "]");
                if($phpIMAP->delete($message['uid'])){
                  $this->output("Deleted");
                }
              }
            }
          } else {
            $this->output("No Messages");
          }
        }
      }
    }
    $this->output("Fetcher completed its run");
  }
}
