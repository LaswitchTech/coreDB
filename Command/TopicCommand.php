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

  public function addAction($argv){
    $this->Topic->addUniqueKey($argv);
    $this->success("Key(s) added");
  }

  public function generateAction($argv){

    // Intro
    $this->output("==================================================================================================");
    $this->output("Starting Topics Generation...");
    $this->output("==================================================================================================");

    // Initiate treatedTopics
    $treatedTopics = [];

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
      if(!isset($eml['reference_id'])){ $eml['reference_id'] = []; }

      // Update Null Values
      if($eml['meta'] == null){ $eml['meta'] = []; }
      if($eml['dataset'] == null){ $eml['dataset'] = []; }
      if($eml['files'] == null){ $eml['files'] = []; }
      if($eml['sharedTo'] == null){ $eml['sharedTo'] = []; }
      if($eml['topics'] == null){ $eml['topics'] = []; }
      if($eml['reference_id'] == null){ $eml['reference_id'] = []; }

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
          if(!in_array($topic['id'],$eml['topics']) && $topic['status'] < 2){

            // Output
            $this->output("Found Topic: [".$topic['id']."]");

            // Update foundTopic Switch
            $foundTopic = true;

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
            if($topic['status'] > 0 && $topic['countUnread'] > 0){ $topic['status'] = 0; }
            if($topic['status'] <= 0 && $topic['countUnread'] <= 0){ $topic['status'] = 1; }

            // Save Topic Changes
            $this->output("Save Topic Changes");
            $topicID = $this->Topic->updateTopic($topic);

            // Update treatedTopics
            if(!in_array($topic['id'],$treatedTopics)){ $treatedTopics[] = $topic['id']; }
          }
        }
      }

      // Merge in Topics by Reply To ID
      if(isset($eml['reply_to_id']) && $eml['reply_to_id'] != null){
        $topics = $this->Topic->getTopics(['mids' => $eml['reply_to_id']]);
        if(count($topics) > 0){
          $this->output("Merge in Topics by Reply To ID");
          foreach($topics as $key => $topic){
            if(!in_array($topic['id'],$eml['topics']) && $topic['status'] < 2){

              // Output
              $this->output("Found Topic: [".$topic['id']."]");

              // Update foundTopic Switch
              $foundTopic = true;

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
              if($topic['status'] > 0 && $topic['countUnread'] > 0){ $topic['status'] = 0; }
              if($topic['status'] <= 0 && $topic['countUnread'] <= 0){ $topic['status'] = 1; }

              // Save Topic Changes
              $this->output("Save Topic Changes");
              $topicID = $this->Topic->updateTopic($topic);

              // Update treatedTopics
              if(!in_array($topic['id'],$treatedTopics)){ $treatedTopics[] = $topic['id']; }
            }
          }
        }
      }

      // Merge in Topics by Reference ID
      if(isset($eml['reference_id']) && $eml['reference_id'] != null){
        $this->output("Merge in Topics by Reference ID");
        foreach($eml['reference_id'] as $mid){
          $topics = $this->Topic->getTopics(['mids' => $mid]);
          if(count($topics) > 0){
            foreach($topics as $key => $topic){
              if(!in_array($topic['id'],$eml['topics']) && $topic['status'] < 2){

                // Output
                $this->output("Found Topic: [".$topic['id']."]");

                // Update foundTopic Switch
                $foundTopic = true;

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
                if($topic['status'] > 0 && $topic['countUnread'] > 0){ $topic['status'] = 0; }
                if($topic['status'] <= 0 && $topic['countUnread'] <= 0){ $topic['status'] = 1; }

                // Save Topic Changes
                $this->output("Save Topic Changes");
                $topicID = $this->Topic->updateTopic($topic);

                // Update treatedTopics
                if(!in_array($topic['id'],$treatedTopics)){ $treatedTopics[] = $topic['id']; }
              }
            }
          }
        }
      }

      // Merge in Topics by Dataset
      if(isset($eml['dataset']) && $eml['dataset'] != null && count($eml['dataset']) > 0){
        $this->output("Merge in Topics by Dataset");
        foreach($eml['dataset'] as $datasetKey => $datasetValues){
          if(in_array($datasetKey,$this->Topic->getUniqueKeys())){
            if(!is_array($datasetValues)){ $datasetValues = [$datasetValues]; }
            foreach($datasetValues as $datasetValue){
              $topics = $this->Topic->getTopics(['dataset' => $datasetValue]);
              if(count($topics) > 0){
                foreach($topics as $key => $topic){
                  if(count($topic['dataset']) > 0){
                    if(isset($topic['dataset'][$datasetKey]) && in_array($datasetValue,$topic['dataset'][$datasetKey])){
                      if(!in_array($topic['id'],$eml['topics']) && $topic['status'] < 2){

                        // Output
                        $this->output("Found Topic: [".$topic['id']."]");

                        // Update foundTopic Switch
                        $foundTopic = true;

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
                        if($topic['status'] > 0 && $topic['countUnread'] > 0){ $topic['status'] = 0; }
                        if($topic['status'] <= 0 && $topic['countUnread'] <= 0){ $topic['status'] = 1; }

                        // Save Topic Changes
                        $this->output("Save Topic Changes");
                        $topicID = $this->Topic->updateTopic($topic);

                        // Update treatedTopics
                        if(!in_array($topic['id'],$treatedTopics)){ $treatedTopics[] = $topic['id']; }
                      }
                    }
                  }
                }
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

          // Update treatedTopics
          if(!in_array($topicID,$treatedTopics)){ $treatedTopics[] = $topicID; }
        }
      }

      // Save Message Changes
      $this->output("Save Message Changes");
      $this->IMAP->updateEml($eml);
    }

    // Outro
    $this->output("__________________________________________________________________________________________________");
    $this->output(count($treatedTopics) . " Topic(s) created or modified");
    $this->output(count($emls) . " Messages(s) treated");
    $this->output("##################################################################################################");
  }

  public function mergeAction($argv){

    // Intro
    $this->output("==================================================================================================");
    $this->output("Starting Topics Merge...");
    $this->output("==================================================================================================");

    // Initiate treatedTopics
    $treatedTopics = [];

    // Retrieving Active Topics
    $this->output("Retrieving Active Topics");
    $topics = $this->Topic->getTopics(['status<' => 2]);
    foreach($topics as $topic){

      // Don't process topics that were already processed
      if(!in_array($topic['id'],$treatedTopics)){

        // Merge Based on Message IDs
        if(is_array($topic['mids']) && count($topic['mids']) > 0){
          foreach($topic['mids'] as $mid){
            $searchTopics = $this->Topic->getTopics(['status<' => 3,'mids' => $mid]);
            if(count($searchTopics) > 0){
              foreach($searchTopics as $searchTopic){
                if($searchTopic['id'] != $topic['id']){

                  // Output
                  $this->output("Found Topic: [".$searchTopic['id']."]");

                  // Merge Data from searchTopic to topic
                  $topic['meta'] = $this->Topic->mergeArray($topic['meta'],$searchTopic['meta']);
                  $topic['dataset'] = $this->Topic->mergeArray($topic['dataset'],$searchTopic['dataset']);
                  foreach($searchTopic['emls'] as $value){
                    if(!in_array($value,$topic['emls'])){ $topic['emls'][] = $value; }
                  }
                  foreach($searchTopic['mids'] as $value){
                    if(!in_array($value,$topic['mids'])){ $topic['mids'][] = $value; }
                  }
                  foreach($searchTopic['files'] as $value){
                    if(!in_array($value,$topic['files'])){ $topic['files'][] = $value; }
                  }
                  foreach($searchTopic['contacts'] as $value){
                    if(!in_array($value,$topic['contacts'])){ $topic['contacts'][] = $value; }
                  }
                  foreach($searchTopic['sharedTo'] as $value){
                    if(!in_array($value,$topic['sharedTo'])){ $topic['sharedTo'][] = $value; }
                  }

                  // Update searchTopic
                  $searchTopic['status'] = 3;

                  // Save searchTopic Changes
                  $this->output("Save Topic[".$searchTopic['id']."] Changes");
                  $topicID = $this->Topic->updateTopic($searchTopic);

                  // Update treatedTopics
                  if(!in_array($searchTopic['id'],$treatedTopics)){ $treatedTopics[] = $searchTopic['id']; }
                }
              }
            }
          }
        }

        // Merge Based on Dataset
        if(is_array($topic['dataset']) && count($topic['dataset']) > 0){
          foreach($topic['dataset'] as $key => $dataset){
            if(in_array($key,$this->Topic->getUniqueKeys())){
              if(!is_array($dataset)){ $dataset = [$dataset]; }
              foreach($dataset as $datasetValue){
                $searchTopics = $this->Topic->getTopics(['status<' => 3,'dataset' => $datasetValue]);
                if(count($searchTopics) > 0){
                  foreach($searchTopics as $searchTopic){
                    if(count($searchTopic['dataset']) > 0){
                      if(isset($searchTopic['dataset'][$key]) && in_array($datasetValue,$searchTopic['dataset'][$key])){
                        if($searchTopic['id'] != $topic['id']){

                          // Output
                          $this->output("Found Topic: [".$searchTopic['id']."]");

                          // Merge Data from searchTopic to topic
                          $topic['meta'] = $this->Topic->mergeArray($topic['meta'],$searchTopic['meta']);
                          $topic['dataset'] = $this->Topic->mergeArray($topic['dataset'],$searchTopic['dataset']);
                          foreach($searchTopic['emls'] as $value){
                            if(!in_array($value,$topic['emls'])){ $topic['emls'][] = $value; }
                          }
                          foreach($searchTopic['mids'] as $value){
                            if(!in_array($value,$topic['mids'])){ $topic['mids'][] = $value; }
                          }
                          foreach($searchTopic['files'] as $value){
                            if(!in_array($value,$topic['files'])){ $topic['files'][] = $value; }
                          }
                          foreach($searchTopic['contacts'] as $value){
                            if(!in_array($value,$topic['contacts'])){ $topic['contacts'][] = $value; }
                          }
                          foreach($searchTopic['sharedTo'] as $value){
                            if(!in_array($value,$topic['sharedTo'])){ $topic['sharedTo'][] = $value; }
                          }

                          // Update searchTopic
                          $searchTopic['status'] = 3;

                          // Save searchTopic Changes
                          $this->output("Save Topic[".$searchTopic['id']."] Changes");
                          $topicID = $this->Topic->updateTopic($searchTopic);

                          // Update treatedTopics
                          if(!in_array($searchTopic['id'],$treatedTopics)){ $treatedTopics[] = $searchTopic['id']; }
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        }

        // Update all emls topics
        foreach($topic['emls'] as $id){
          $emls = $this->IMAP->getEmls(['id' => $id]);
          if(count($emls) > 0){
            foreach($emls as $eml){
              if(!in_array($topic['id'],$eml['topics'])){

                // Update Message
                $this->output("Updating Message [".$eml['id']."]");
                $eml['topics'][] = $topic['id'];

                // Save Message Changes
                $this->output("Save Message Changes");
                $this->IMAP->updateEml($eml);
              }
            }
          }
        }

        // Update only if changes were made
        if(count($treatedTopics) > 0){

          // Output
          $this->output("Treating [".$topic['id']."]");

          // Update Topic Unread Count
          $topic['countUnread'] = count($this->IMAP->getEmls(['isRead' => 0,'topics' => $topic['id']]));
          if($topic['status'] > 0 && $topic['countUnread'] > 0){ $topic['status'] = 0; }
          if($topic['status'] <= 0 && $topic['countUnread'] <= 0){ $topic['status'] = 1; }

          // Save Topic Changes
          $this->output("Save Topic Changes");
          $topicID = $this->Topic->updateTopic($topic);

          // Update treatedTopics
          if(!in_array($topic['id'],$treatedTopics)){ $treatedTopics[] = $topic['id']; }
        }
      }
    }

    // Outro
    $this->output("__________________________________________________________________________________________________");
    $this->output(count($treatedTopics) . " Topic(s) merged");
    $this->output("##################################################################################################");
  }
}
