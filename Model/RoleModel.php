<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

class RoleModel extends BaseModel {

  public function getRole($name, $convert = true) {
    $roles = $this->select("SELECT * FROM auth_roles WHERE name = ? ORDER BY id ASC", [$name]);
    if($convert){
      foreach($roles as $rkey => $role){
        $roles[$rkey]['members'] = json_decode($role['members'],true);
        $roles[$rkey]['permissions'] = json_decode($role['permissions'],true);
      }
    }
    return $roles;
  }

  public function getRoles($limit) {
    return $this->select("SELECT * FROM auth_roles ORDER BY id ASC LIMIT ?", [$limit]);
  }

  public function deleteRole($name) {
    return $this->delete("DELETE FROM auth_roles WHERE name = ?", [$name]);
  }

  public function saveRole($role) {
    if(is_array($role['members'])){ $role['members'] = json_encode($role['members'],JSON_UNESCAPED_SLASHES); }
    if(is_array($role['permissions'])){ $role['permissions'] = json_encode($role['permissions'],JSON_UNESCAPED_SLASHES); }
    return $this->update("UPDATE auth_roles SET members = ?, permissions = ? WHERE name = ?", [$role['members'],$role['permissions'],$role['name']]);
  }

  public function addRole($name, $permissions = [], $members = []) {
    return $this->insert("INSERT INTO auth_roles (name, permissions, members) VALUES (?,?,?)", [$name,json_encode($permissions,JSON_UNESCAPED_SLASHES),json_encode($members,JSON_UNESCAPED_SLASHES)]);
  }

  public function setDefault($name) {
    return $this->update("UPDATE auth_roles SET isDefault = ? WHERE name = ?", [1,$name]);
  }

  public function removeDefault($name) {
    return $this->update("UPDATE auth_roles SET isDefault = ? WHERE name = ?", [0,$name]);
  }
}
