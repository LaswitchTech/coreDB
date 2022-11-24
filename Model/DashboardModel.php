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
}
