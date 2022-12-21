<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

class RoleModel extends BaseModel {

  public function getRole($id, $convert = true) {
    $roles = $this->select("SELECT * FROM roles WHERE name = ? ORDER BY id ASC", [$id]);
    if($convert){
      foreach($roles as $rkey => $role){
        $members = json_decode($role['members'],true);
        foreach($members as $mkey => $member){
          $table = array_key_first($member);
          $record = $this->select("SELECT * FROM ".$table." WHERE id = ? ORDER BY id ASC", [$member[$table]]);
          if(count($record) > 0){
            $record = $record[0];
            if(isset($record['username'])){ $members[$mkey][$table] = $record['username']; }
            if(isset($record['name'])){ $members[$mkey][$table] = $record['name']; }
          }
        }
        $roles[$rkey]['members'] = json_encode($members,JSON_UNESCAPED_SLASHES);
      }
    }
    return $roles;
  }

  public function getRoles($limit) {
    return $this->select("SELECT * FROM roles ORDER BY id ASC LIMIT ?", [$limit]);
  }

  public function deleteRole($id) {
    return $this->delete("DELETE FROM roles WHERE name = ?", [$id]);
  }

  public function saveRole($role) {
    if(is_array($role['members'])){ $role['members'] = json_encode($role['members'],JSON_UNESCAPED_SLASHES); }
    if(is_array($role['permissions'])){ $role['permissions'] = json_encode($role['permissions'],JSON_UNESCAPED_SLASHES); }
    return $this->update("UPDATE roles SET members = ?, permissions = ? WHERE name = ?", [$role['members'],$role['permissions'],$role['name']]);
  }

  public function addRole($name, $permissions = [], $members = []) {
    return $this->insert("INSERT INTO roles (name, permissions, members) VALUES (?,?,?)", [$name,json_encode($permissions,JSON_UNESCAPED_SLASHES),json_encode($members,JSON_UNESCAPED_SLASHES)]);
  }
}
