<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

class WidgetModel extends BaseModel {
  public function getWidget($id) {
    return $this->select("SELECT * FROM widgets WHERE name = ? ORDER BY id ASC", [$id]);
  }
  public function getWidgets($limit) {
    if($limit <= 0){
      return $this->select("SELECT * FROM widgets ORDER BY id ASC");
    }
    return $this->select("SELECT * FROM widgets ORDER BY id ASC LIMIT ?", [$limit]);
  }
}
