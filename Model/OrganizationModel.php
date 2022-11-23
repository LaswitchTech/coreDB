<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

class OrganizationModel extends BaseModel {
  public function getOrganizations() {
    return $this->select("SELECT * FROM organizations ORDER BY id ASC", []);
  }
}
