<?php
//Import Auth class into the global namespace
//These must be at the top of your script, not inside a function
use LaswitchTech\phpDB\Database;
?>
<h1>Install</h1>
<ul>
  <li><a href="/">Index</a></li>
  <li><a href="/install">Install</a></li>
  <li><a href="/info">Info</a></li>
  <li><a href="/signin">Sign In</a></li>
  <li><a href="?signout">Sign Out</a></li>
</ul>
<?php
//Demo Data?
$demo = true;
//Initiate Database
$phpDB = new Database();
$phpDB->drop('users');
$phpDB->create('users',[
  'id' => [
    'type' => 'BIGINT(10)',
    'extra' => ['UNSIGNED','AUTO_INCREMENT','PRIMARY KEY']
  ],
  'created' => [
    'type' => 'DATETIME',
    'extra' => ['DEFAULT CURRENT_TIMESTAMP']
  ],
  'modified' => [
    'type' => 'DATETIME',
    'extra' => ['DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP']
  ],
  'username' => [
    'type' => 'VARCHAR(60)',
    'extra' => ['NOT NULL','UNIQUE']
  ],
  'type' => [
    'type' => 'VARCHAR(10)',
    'extra' => ['NOT NULL','DEFAULT "SQL"']
  ],
  'roles' => [
    'type' => 'LONGTEXT',
    'extra' => ['NULL']
  ],
  'sessionID' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NULL']
  ],
  'password' => [
    'type' => 'VARCHAR(100)',
    'extra' => ['NOT NULL']
  ],
  'token' => [
    'type' => 'VARCHAR(100)',
    'extra' => ['NOT NULL','UNIQUE']
  ],
  'isActive' => [
    'type' => 'int(1)',
    'extra' => ['NULL']
  ]
]);
if($demo){
  $UserID = $phpDB->insert("INSERT INTO users (username, password, token) VALUES (?,?,?)", ["user1@domain.com",password_hash("pass1", PASSWORD_DEFAULT),hash("sha256", "pass1", false)]);
}
$phpDB->drop('relationships');
$phpDB->create('relationships',[
  'id' => [
    'type' => 'BIGINT(10)',
    'extra' => ['UNSIGNED','AUTO_INCREMENT','PRIMARY KEY']
  ],
  'created' => [
    'type' => 'DATETIME',
    'extra' => ['DEFAULT CURRENT_TIMESTAMP']
  ],
  'modified' => [
    'type' => 'DATETIME',
    'extra' => ['DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP']
  ],
  'owner' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NULL']
  ],
  'relations' => [
    'type' => 'LONGTEXT',
    'extra' => ['NULL']
  ]
]);
// if($demo){
//   $phpDB->insert("INSERT INTO relationships (owner,relations) VALUES (?,?)", ["",""]);
// }
$phpDB->drop('permissions');
$phpDB->create('permissions',[
  'id' => [
    'type' => 'BIGINT(10)',
    'extra' => ['UNSIGNED','AUTO_INCREMENT','PRIMARY KEY']
  ],
  'created' => [
    'type' => 'DATETIME',
    'extra' => ['DEFAULT CURRENT_TIMESTAMP']
  ],
  'modified' => [
    'type' => 'DATETIME',
    'extra' => ['DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP']
  ],
  'name' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NOT NULL','UNIQUE']
  ]
]);
if($demo){
  $permissions = [
    "permission/list" => ["administrators"],
    "role/list" => ["administrators"],
    "organization/list" => ["administrators"],
    "notification/list" => ["administrators","users"],
    "notification/read" => ["administrators","users"],
    "activity/list" => ["administrators","users"],
    "View/index.php" => ["administrators","users"],
    "View/settings.php" => ["administrators","users"],
    "View/profile.php" => ["administrators","users"],
  ];
  foreach($permissions as $permission => $roles){
    $phpDB->insert("INSERT INTO permissions (name) VALUES (?)", [$permission]);
  }
}
$phpDB->drop('roles');
$phpDB->create('roles',[
  'id' => [
    'type' => 'BIGINT(10)',
    'extra' => ['UNSIGNED','AUTO_INCREMENT','PRIMARY KEY']
  ],
  'created' => [
    'type' => 'DATETIME',
    'extra' => ['DEFAULT CURRENT_TIMESTAMP']
  ],
  'modified' => [
    'type' => 'DATETIME',
    'extra' => ['DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP']
  ],
  'name' => [
    'type' => 'VARCHAR(60)',
    'extra' => ['NOT NULL','UNIQUE']
  ],
  'permissions' => [
    'type' => 'LONGTEXT',
    'extra' => ['NULL']
  ],
  'members' => [
    'type' => 'LONGTEXT',
    'extra' => ['NULL']
  ]
]);
if($demo){
  $values = [];
  $name = "users";
  foreach($permissions as $permission => $roles){
    if(in_array($name,$roles)){
      $values[$permission] = 1;
    }
  }
  $RoleID = $phpDB->insert("INSERT INTO roles (name, permissions, members) VALUES (?,?,?)", [$name,json_encode($values,JSON_UNESCAPED_SLASHES),json_encode([],JSON_UNESCAPED_SLASHES)]);
  $values = [];
  $name = "administrators";
  foreach($permissions as $permission => $roles){
    if(in_array($name,$roles)){
      $values[$permission] = 1;
    }
  }
  $RoleID = $phpDB->insert("INSERT INTO roles (name, permissions, members) VALUES (?,?,?)", [$name,json_encode($values,JSON_UNESCAPED_SLASHES),json_encode([["users" => $UserID]],JSON_UNESCAPED_SLASHES)]);
  $phpDB->update("UPDATE users SET roles = ? WHERE id = ?", [json_encode([["roles" => $RoleID]],JSON_UNESCAPED_SLASHES),$UserID]);
}
$phpDB->drop('sessions');
$phpDB->create('sessions',[
  'id' => [
    'type' => 'BIGINT(10)',
    'extra' => ['UNSIGNED','AUTO_INCREMENT','PRIMARY KEY']
  ],
  'created' => [
    'type' => 'DATETIME',
    'extra' => ['DEFAULT CURRENT_TIMESTAMP']
  ],
  'modified' => [
    'type' => 'DATETIME',
    'extra' => ['DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP']
  ],
  'sessionID' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NOT NULL','UNIQUE']
  ],
  'userID' => [
    'type' => 'BIGINT(10)',
    'extra' => ['NOT NULL']
  ],
  'userAgent' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NULL']
  ],
  'userBrowser' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NULL']
  ],
  'userIP' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NULL']
  ],
  'userData' => [
    'type' => 'LONGTEXT',
    'extra' => ['NULL']
  ],
  'userConsent' => [
    'type' => 'LONGTEXT',
    'extra' => ['NULL']
  ],
  'userActivity' => [
    'action' => 'ADD',
    'type' => 'DATETIME',
    'extra' => ['DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP']
  ]
]);
$phpDB->drop('notifications');
$phpDB->create('notifications',[
  'id' => [
    'type' => 'BIGINT(10)',
    'extra' => ['UNSIGNED','AUTO_INCREMENT','PRIMARY KEY']
  ],
  'created' => [
    'type' => 'DATETIME',
    'extra' => ['DEFAULT CURRENT_TIMESTAMP']
  ],
  'modified' => [
    'type' => 'DATETIME',
    'extra' => ['DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP']
  ],
  'content' => [
    'type' => 'LONGTEXT',
    'extra' => ['NULL']
  ],
  'route' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NULL']
  ],
  'color' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NOT NULL','DEFAULT "primary"']
  ],
  'userID' => [
    'type' => 'BIGINT(10)',
    'extra' => ['NOT NULL']
  ],
  'isRead' => [
    'type' => 'int(1)',
    'extra' => ['NOT NULL','DEFAULT "0"']
  ]
]);
if($demo){
  $phpDB->insert("INSERT INTO notifications (content, route, userID) VALUES (?,?,?)", ["There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...","/hello",$UserID]);
  $phpDB->insert("INSERT INTO notifications (content, route, userID) VALUES (?,?,?)", ["There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...","/hello",$UserID]);
  $phpDB->insert("INSERT INTO notifications (content, route, userID) VALUES (?,?,?)", ["There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...","/hello",$UserID]);
  $phpDB->insert("INSERT INTO notifications (content, route, userID) VALUES (?,?,?)", ["There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...","/hello",$UserID]);
  $phpDB->insert("INSERT INTO notifications (content, route, userID) VALUES (?,?,?)", ["There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...","/hello",$UserID]);
}
$phpDB->drop('activities');
$phpDB->create('activities',[
  'id' => [
    'type' => 'BIGINT(10)',
    'extra' => ['UNSIGNED','AUTO_INCREMENT','PRIMARY KEY']
  ],
  'created' => [
    'type' => 'DATETIME',
    'extra' => ['DEFAULT CURRENT_TIMESTAMP']
  ],
  'modified' => [
    'type' => 'DATETIME',
    'extra' => ['DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP']
  ],
  'title' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NULL']
  ],
  'content' => [
    'type' => 'LONGTEXT',
    'extra' => ['NULL']
  ],
  'route' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NULL']
  ],
  'color' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NOT NULL','DEFAULT "primary"']
  ],
  'icon' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NOT NULL','DEFAULT "activity"']
  ],
  'owner' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NOT NULL']
  ]
]);
if($demo){
  $phpDB->insert("INSERT INTO activities (title, content, route, owner) VALUES (?,?,?,?)", ["Lorem ipsum", "There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...","/hello",json_encode(["users" => $UserID],JSON_UNESCAPED_SLASHES)]);
  $phpDB->insert("INSERT INTO activities (title, content, route, owner) VALUES (?,?,?,?)", ["Lorem ipsum", "There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...","/hello",json_encode(["users" => $UserID],JSON_UNESCAPED_SLASHES)]);
  $phpDB->insert("INSERT INTO activities (title, content, route, owner) VALUES (?,?,?,?)", ["Lorem ipsum", "There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...","/hello",json_encode(["users" => $UserID],JSON_UNESCAPED_SLASHES)]);
  $phpDB->insert("INSERT INTO activities (title, content, route, owner) VALUES (?,?,?,?)", ["Lorem ipsum", "There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...","/hello",json_encode(["users" => $UserID],JSON_UNESCAPED_SLASHES)]);
  $phpDB->insert("INSERT INTO activities (title, content, route, owner) VALUES (?,?,?,?)", ["Lorem ipsum", "There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...","/hello",json_encode(["users" => $UserID],JSON_UNESCAPED_SLASHES)]);
}
$phpDB->drop('organizations');
$phpDB->create('organizations',[
  'id' => [
    'type' => 'BIGINT(10)',
    'extra' => ['UNSIGNED','AUTO_INCREMENT','PRIMARY KEY']
  ],
  'created' => [
    'type' => 'DATETIME',
    'extra' => ['DEFAULT CURRENT_TIMESTAMP']
  ],
  'modified' => [
    'type' => 'DATETIME',
    'extra' => ['DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP']
  ],
  'name' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NOT NULL','UNIQUE']
  ],
  'sbrn' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NULL','UNIQUE']
  ],
  'address' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NULL']
  ],
  'city' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NULL']
  ],
  'state' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NULL']
  ],
  'country' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NULL']
  ],
  'zipcode' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NULL']
  ],
  'email' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NULL']
  ],
  'fax' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NULL']
  ],
  'phone' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NULL']
  ],
  'tollfree' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NULL']
  ],
  'website' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NULL','UNIQUE']
  ],
  'domain' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NULL','UNIQUE']
  ],
  'administrator' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NULL']
  ],
  'isActive' => [
    'type' => 'int(1)',
    'extra' => ['NULL']
  ]
]);
// if($demo){
//   $phpDB->insert("INSERT INTO organizations (owner,relations) VALUES (?,?)", ["",""]);
// }
?>
<p>Installation Complete!</p>
