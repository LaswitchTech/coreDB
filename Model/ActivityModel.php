<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

class ActivityModel extends BaseModel {
  public function getActivities($owner, $limit) {
    return $this->select("SELECT * FROM activities WHERE owner = ? ORDER BY id DESC LIMIT ?", [json_encode($owner, JSON_UNESCAPED_SLASHES),$limit]);
  }
}
