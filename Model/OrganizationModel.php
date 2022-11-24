<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

class OrganizationModel extends BaseModel {
  public function getOrganization($id) {
    return $this->select("SELECT * FROM organizations WHERE id = ? ORDER BY id ASC", [$id]);
  }
  public function getOrganizations($limit) {
    return $this->select("SELECT * FROM organizations ORDER BY id ASC LIMIT ?", [$limit]);
  }
}
