<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

class DashboardModel extends BaseModel {
  public function getDashboard($owner) {
    return $this->select("SELECT * FROM dashboards WHERE owner = ? ORDER BY id ASC", [json_encode($owner,JSON_UNESCAPED_SLASHES)]);
  }
  public function getDashboards($limit) {
    return $this->select("SELECT * FROM dashboards ORDER BY id ASC LIMIT ?", [$limit]);
  }
  public function saveDashboard($owner,$layout) {
    $find = $this->getDashboard($owner);
    if(count($find) > 0){
      $this->update("UPDATE dashboards SET layout = ? WHERE id = ?", [json_encode($layout,JSON_UNESCAPED_SLASHES),$find[0]['id']]);
      return $find;
    } else {
      $id = $this->insert("INSERT INTO dashboards (owner,layout) VALUES (?,?)", [json_encode($owner,JSON_UNESCAPED_SLASHES),json_encode($layout,JSON_UNESCAPED_SLASHES)]);
      return $this->select("SELECT * FROM dashboards WHERE id = ?", [$id]);
    }
  }
}
