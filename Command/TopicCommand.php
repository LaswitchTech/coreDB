<?php

//Import BaseCommand class into the global namespace
use LaswitchTech\phpCLI\BaseCommand;

class TopicCommand extends BaseCommand {

  protected $IMAP = null;
  protected $Topic = null;
  protected $Activity = null;
  protected $Notification = null;

  public function __construct(){

    // Initiate Parent Constructor
    parent::__construct();

    // Setup ImapModel
    $this->IMAP = new ImapModel();

    // Setup TopicModel
    $this->Topic = new TopicModel();

    // Setup ActivityModel
    $this->Activity = new ActivityModel();

    // Setup NotificationModel
    $this->Notification = new NotificationModel();
  }

  public function generateAction($argv){

    // Intro
    $this->output("Starting Topics Generation...");

    // Retrieving Unlinked Messages
    $this->output("Retrieving Unlinked Messages");
    $emls = $this->IMAP->getEmls(['isLinked' => 0]);
    foreach($emls as $eml){

      // Update Meta References
      $this->output("Update Meta");
      $eml['meta'] = $this->Topic->parseMeta($eml['meta']);

      // Update Empty Values
      if(!isset($eml['meta'])){ $eml['meta'] = []; }
      if(!isset($eml['dataset'])){ $eml['dataset'] = []; }
      if(!isset($eml['files'])){ $eml['files'] = []; }
      if(!isset($eml['sharedTo'])){ $eml['sharedTo'] = []; }
      if(!isset($eml['topics'])){ $eml['topics'] = []; }

      // Update Null Values
      if($eml['meta'] == null){ $eml['meta'] = []; }
      if($eml['dataset'] == null){ $eml['dataset'] = []; }
      if($eml['files'] == null){ $eml['files'] = []; }
      if($eml['sharedTo'] == null){ $eml['sharedTo'] = []; }
      if($eml['topics'] == null){ $eml['topics'] = []; }

      // Update Meta References
      $this->output("Update Dataset");
      $eml['dataset'] = $this->Topic->dataset($eml);

      // Update sharedTo
      $eml['sharedTo'] = $this->Topic->sharedTo($eml);

      // Initiate foundTopic Switch
      $foundTopic = false;

      // Merge in Topics by Message ID
      $topics = $this->Topic->getTopics(['mids' => $eml['mid']]);
      if(count($topics) > 0){
        $this->output("Merge in Topics by Message ID");
        foreach($topics as $key => $topic){
          if($topic['status'] < 2){

            // Output
            $this->output("Found Topic: [".$topic['id']."]");

            // Update foundTopic Switch
            $foundTopic = true;
            if($topic['status'] > 0){ $topic['status'] = 0; }

            // Update Message's Topic Dataset and isLinked Switch
            if($eml['isLinked'] < 1){ $eml['isLinked'] = 1; }
            if(!in_array($topic['id'],$eml['topics'])){ $eml['topics'][] = $topic['id']; }

            // Update Topic Dataset
            if(!in_array($eml['mid'],$topic['mids'])){ $topic['mids'][] = $eml['mid']; }
            if(!in_array($eml['id'],$topic['emls'])){ $topic['emls'][] = $eml['id']; }
            if(!in_array($eml['account'],$topic['contacts'])){ $topic['contacts'][] = $eml['account']; }
            if(!in_array($eml['sender'],$topic['contacts'])){ $topic['contacts'][] = $eml['sender']; }
            if(!in_array($eml['from'],$topic['contacts'])){ $topic['contacts'][] = $eml['from']; }
            if(!in_array($eml['reply_to_id'],$topic['mids'])){ $topic['mids'][] = $eml['reply_to_id']; }
            foreach($eml['files'] as $value){
              if(!in_array($value,$topic['files'])){ $topic['files'][] = $value; }
            }
            foreach($eml['reference_id'] as $value){
              if(!in_array($value,$topic['mids'])){ $topic['mids'][] = $value; }
            }
            foreach($eml['to'] as $value){
              if(!in_array($value,$topic['contacts'])){ $topic['contacts'][] = $value; }
            }
            foreach($eml['cc'] as $value){
              if(!in_array($value,$topic['contacts'])){ $topic['contacts'][] = $value; }
            }
            foreach($eml['bcc'] as $value){
              if(!in_array($value,$topic['contacts'])){ $topic['contacts'][] = $value; }
            }
            foreach($eml['sharedTo'] as $value){
              if(!in_array($value,$topic['sharedTo'])){ $topic['sharedTo'][] = $value; }
            }
            if(!is_array($topic['meta'])){ $topic['meta'] = []; }
            $topic['meta'] = $this->Topic->mergeArray($topic['meta'],$eml['meta']);
            if(!is_array($topic['dataset'])){ $topic['dataset'] = []; }
            $topic['dataset'] = $this->Topic->mergeArray($topic['dataset'],$eml['dataset']);

            // Update Topic Unread Count
            $topic['countUnread'] = count($this->IMAP->getEmls(['isRead' => 0,'topics' => $topic['id']]));

            // Save Topic Changes
            $this->output("Save Topic Changes");
            $topicID = $this->Topic->updateTopic($topic);
          }
        }
      }

      // Merge in Topics by Reply To ID
      if(isset($eml['reply_to_id']) && $eml['reply_to_id'] != null){
        $topics = $this->Topic->getTopics(['mids' => $eml['reply_to_id']]);
        if(count($topics) > 0){
          $this->output("Merge in Topics by Message ID");
          foreach($topics as $key => $topic){
            if($topic['status'] < 2){

              // Output
              $this->output("Found Topic: [".$topic['id']."]");

              // Update foundTopic Switch
              $foundTopic = true;
              if($topic['status'] > 0){ $topic['status'] = 0; }

              // Update Message's Topic Dataset and isLinked Switch
              if($eml['isLinked'] < 1){ $eml['isLinked'] = 1; }
              if(!in_array($topic['id'],$eml['topics'])){ $eml['topics'][] = $topic['id']; }

              // Update Topic Dataset
              if(!in_array($eml['mid'],$topic['mids'])){ $topic['mids'][] = $eml['mid']; }
              if(!in_array($eml['id'],$topic['emls'])){ $topic['emls'][] = $eml['id']; }
              if(!in_array($eml['account'],$topic['contacts'])){ $topic['contacts'][] = $eml['account']; }
              if(!in_array($eml['sender'],$topic['contacts'])){ $topic['contacts'][] = $eml['sender']; }
              if(!in_array($eml['from'],$topic['contacts'])){ $topic['contacts'][] = $eml['from']; }
              if(!in_array($eml['reply_to_id'],$topic['mids'])){ $topic['mids'][] = $eml['reply_to_id']; }
              foreach($eml['files'] as $value){
                if(!in_array($value,$topic['files'])){ $topic['files'][] = $value; }
              }
              foreach($eml['reference_id'] as $value){
                if(!in_array($value,$topic['mids'])){ $topic['mids'][] = $value; }
              }
              foreach($eml['to'] as $value){
                if(!in_array($value,$topic['contacts'])){ $topic['contacts'][] = $value; }
              }
              foreach($eml['cc'] as $value){
                if(!in_array($value,$topic['contacts'])){ $topic['contacts'][] = $value; }
              }
              foreach($eml['bcc'] as $value){
                if(!in_array($value,$topic['contacts'])){ $topic['contacts'][] = $value; }
              }
              foreach($eml['sharedTo'] as $value){
                if(!in_array($value,$topic['sharedTo'])){ $topic['sharedTo'][] = $value; }
              }
              if(!is_array($topic['meta'])){ $topic['meta'] = []; }
              $topic['meta'] = $this->Topic->mergeArray($topic['meta'],$eml['meta']);
              if(!is_array($topic['dataset'])){ $topic['dataset'] = []; }
              $topic['dataset'] = $this->Topic->mergeArray($topic['dataset'],$eml['dataset']);

              // Update Topic Unread Count
              $topic['countUnread'] = count($this->IMAP->getEmls(['isRead' => 0,'topics' => $topic['id']]));

              // Save Topic Changes
              $this->output("Save Topic Changes");
              $topicID = $this->Topic->updateTopic($topic);
            }
          }
        }
      }

      // Merge in Topics by Reference ID
      if(isset($eml['reference_id']) && $eml['reference_id'] != null){
        foreach($eml['reference_id'] as $mid){
          $topics = $this->Topic->getTopics(['mids' => $mid]);
          if(count($topics) > 0){
            $this->output("Merge in Topics by Message ID");
            foreach($topics as $key => $topic){
              if($topic['status'] < 2){

                // Output
                $this->output("Found Topic: [".$topic['id']."]");

                // Update foundTopic Switch
                $foundTopic = true;
                if($topic['status'] > 0){ $topic['status'] = 0; }

                // Update Message's Topic Dataset and isLinked Switch
                if($eml['isLinked'] < 1){ $eml['isLinked'] = 1; }
                if(!in_array($topic['id'],$eml['topics'])){ $eml['topics'][] = $topic['id']; }

                // Update Topic Dataset
                if(!in_array($eml['mid'],$topic['mids'])){ $topic['mids'][] = $eml['mid']; }
                if(!in_array($eml['id'],$topic['emls'])){ $topic['emls'][] = $eml['id']; }
                if(!in_array($eml['account'],$topic['contacts'])){ $topic['contacts'][] = $eml['account']; }
                if(!in_array($eml['sender'],$topic['contacts'])){ $topic['contacts'][] = $eml['sender']; }
                if(!in_array($eml['from'],$topic['contacts'])){ $topic['contacts'][] = $eml['from']; }
                if(!in_array($eml['reply_to_id'],$topic['mids'])){ $topic['mids'][] = $eml['reply_to_id']; }
                foreach($eml['files'] as $value){
                  if(!in_array($value,$topic['files'])){ $topic['files'][] = $value; }
                }
                foreach($eml['reference_id'] as $value){
                  if(!in_array($value,$topic['mids'])){ $topic['mids'][] = $value; }
                }
                foreach($eml['to'] as $value){
                  if(!in_array($value,$topic['contacts'])){ $topic['contacts'][] = $value; }
                }
                foreach($eml['cc'] as $value){
                  if(!in_array($value,$topic['contacts'])){ $topic['contacts'][] = $value; }
                }
                foreach($eml['bcc'] as $value){
                  if(!in_array($value,$topic['contacts'])){ $topic['contacts'][] = $value; }
                }
                foreach($eml['sharedTo'] as $value){
                  if(!in_array($value,$topic['sharedTo'])){ $topic['sharedTo'][] = $value; }
                }
                if(!is_array($topic['meta'])){ $topic['meta'] = []; }
                $topic['meta'] = $this->Topic->mergeArray($topic['meta'],$eml['meta']);
                if(!is_array($topic['dataset'])){ $topic['dataset'] = []; }
                $topic['dataset'] = $this->Topic->mergeArray($topic['dataset'],$eml['dataset']);

                // Update Topic Unread Count
                $topic['countUnread'] = count($this->IMAP->getEmls(['isRead' => 0,'topics' => $topic['id']]));

                // Save Topic Changes
                $this->output("Save Topic Changes");
                $topicID = $this->Topic->updateTopic($topic);
              }
            }
          }
        }
      }

      // If no Topic were identified, create one
      if(!$foundTopic){
        $this->output("Creating Topic");
        $topic = [
          'meta' => $eml['meta'],
          'dataset' => $eml['dataset'],
          'emls' => [$eml['id']],
          'mids' => [$eml['mid']],
          'files' => $eml['files'],
          'contacts' => [$eml['account']],
          'sharedTo' => $eml['sharedTo'],
          'countUnread' => 1,
        ];
        if(!in_array($eml['sender'],$topic['contacts'])){ $topic['contacts'][] = $eml['sender']; }
        if(!in_array($eml['from'],$topic['contacts'])){ $topic['contacts'][] = $eml['from']; }
        foreach($eml['to'] as $value){
          if(!in_array($value,$topic['contacts'])){ $topic['contacts'][] = $value; }
        }
        foreach($eml['cc'] as $value){
          if(!in_array($value,$topic['contacts'])){ $topic['contacts'][] = $value; }
        }
        foreach($eml['bcc'] as $value){
          if(!in_array($value,$topic['contacts'])){ $topic['contacts'][] = $value; }
        }
        $topicID = $this->Topic->addTopic($topic);

        // Update Message's Topic Dataset and isLinked Switch
        if($topicID){
          $this->output("Topic: [".$topicID."]");
          if($eml['isLinked'] < 1){ $eml['isLinked'] = 1; }
          if(!in_array($topicID,$eml['topics'])){ $eml['topics'][] = $topicID; }
        }
      }

      // Save Message Changes
      $this->output("Save Message Changes");
      $this->IMAP->updateEml($eml);
    }
  }
}
