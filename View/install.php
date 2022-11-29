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
  'organization' => [
    'type' => 'BIGINT(10)',
    'extra' => ['NULL']
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
    'extra' => ['NOT NULL','DEFAULT "0"']
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
    "isAdministrator" => ["administrators"],
    "permission/list" => ["administrators"],
    "role/list" => ["administrators"],
    "role/get" => ["administrators"],
    "user/list" => ["administrators"],
    "user/get" => ["administrators"],
    "organization/list" => ["administrators"],
    "notification/list" => ["administrators","users"],
    "notification/read" => ["administrators","users"],
    "activity/list" => ["administrators","users"],
    "dashboard/get" => ["administrators","users"],
    "dashboard/save" => ["administrators","users"],
    "widget/list" => ["administrators","users"],
    "widget/get" => ["administrators","users"],
    "View/index.php" => ["administrators","users"],
    "View/settings.php" => ["administrators","users"],
    "View/user.php" => ["administrators"],
    "View/role.php" => ["administrators"],
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
$phpDB->drop('dashboards');
$phpDB->create('dashboards',[
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
    'extra' => ['NOT NULL','UNIQUE']
  ],
  'layout' => [
    'type' => 'LONGTEXT',
    'extra' => ['NULL']
  ]
]);
if($demo){
  $phpDB->insert("INSERT INTO dashboards (owner,layout) VALUES (?,?)", ['{"users":1}','[{"row-cols-4":[{"col":["box-secondary"]},{"col":["box-secondary"]},{"col":["box-secondary"]},{"col":["box-secondary"]},{"col-12":["box-secondary"]}]},{"row-cols-3":[{"col-8":["box-secondary"]},{"col":["box-secondary"]}]}]']);
}
$phpDB->drop('widgets');
$phpDB->create('widgets',[
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
  'element' => [
    'type' => 'LONGTEXT',
    'extra' => ['NOT NULL']
  ],
  'callback' => [
    'type' => 'LONGTEXT',
    'extra' => ['NULL']
  ]
]);
if($demo){
  $phpDB->insert("INSERT INTO widgets (name,element,callback) VALUES (?,?,?)", ['box-primary','<div class="py-4 rounded shadow border bg-primary"></div>','function callback(object){ console.log("box-primary",object); }']);
  $phpDB->insert("INSERT INTO widgets (name,element,callback) VALUES (?,?,?)", ['box-secondary','<div class="py-4 rounded shadow border bg-secondary"></div>','function callback(object){ console.log("box-secondary",object); }']);
  $phpDB->insert("INSERT INTO widgets (name,element,callback) VALUES (?,?,?)", ['box-success','<div class="py-4 rounded shadow border bg-success"></div>','function callback(object){ console.log("box-success",object); }']);
  $phpDB->insert("INSERT INTO widgets (name,element,callback) VALUES (?,?,?)", ['box-danger','<div class="py-4 rounded shadow border bg-danger"></div>','function callback(object){ console.log("box-danger",object); }']);
  $phpDB->insert("INSERT INTO widgets (name,element,callback) VALUES (?,?,?)", ['box-warning','<div class="py-4 rounded shadow border bg-warning"></div>','function callback(object){ console.log("box-warning",object); }']);
  $phpDB->insert("INSERT INTO widgets (name,element,callback) VALUES (?,?,?)", ['box-info','<div class="py-4 rounded shadow border bg-info"></div>','function callback(object){ console.log("box-info",object); }']);
  $phpDB->insert("INSERT INTO widgets (name,element,callback) VALUES (?,?,?)", ['box-light','<div class="py-4 rounded shadow border bg-light"></div>','function callback(object){ console.log("box-light",object); }']);
  $phpDB->insert("INSERT INTO widgets (name,element,callback) VALUES (?,?,?)", ['box-dark','<div class="py-4 rounded shadow border bg-dark"></div>','function callback(object){ console.log("box-dark",object); }']);
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
  'sbrn/irs' => [
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
  ],
]);
// if($demo){
//   $phpDB->insert("INSERT INTO organizations (owner,relations) VALUES (?,?)", ["",""]);
// }
$phpDB->drop('organizations_meta');
$phpDB->create('organizations_meta',[
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
    'extra' => ['NOT NULL','UNIQUE']
  ],
  // Insert JSON Array of Prospect of
  'isProspect' => [
    'type' => 'LONGTEXT',
    'extra' => ['NULL']
  ],
  // Insert JSON Array of Lead of
  'isLead' => [
    'type' => 'LONGTEXT',
    'extra' => ['NULL']
  ],
  // Insert JSON Array of Client of
  'isClient' => [
    'type' => 'LONGTEXT',
    'extra' => ['NULL']
  ],
  // Insert JSON Array of Importer of
  'isImporter' => [
    'type' => 'LONGTEXT',
    'extra' => ['NULL']
  ],
  'isExporter' => [
    'type' => 'int(1)',
    'extra' => ['NULL']
  ],
  'isVendor' => [
    'type' => 'int(1)',
    'extra' => ['NULL']
  ],
  'isBuyer' => [
    'type' => 'int(1)',
    'extra' => ['NULL']
  ],
  'isProducer' => [
    'type' => 'int(1)',
    'extra' => ['NULL']
  ],
  'isShipper' => [
    'type' => 'int(1)',
    'extra' => ['NULL']
  ],
  'isTrucking' => [
    'type' => 'int(1)',
    'extra' => ['NULL']
  ],
  'isShippingLine' => [
    'type' => 'int(1)',
    'extra' => ['NULL']
  ],
  'isAirLine' => [
    'type' => 'int(1)',
    'extra' => ['NULL']
  ],
  'isCarrier' => [
    'type' => 'int(1)',
    'extra' => ['NULL']
  ],
  'isFreightForwarder' => [
    'type' => 'int(1)',
    'extra' => ['NULL']
  ],
  'isBrokerUS' => [
    'type' => 'int(1)',
    'extra' => ['NULL']
  ],
  'isBrokerCA' => [
    'type' => 'int(1)',
    'extra' => ['NULL']
  ],
  'isWharehouse' => [
    'type' => 'int(1)',
    'extra' => ['NULL']
  ],
  'isTerminal' => [
    'type' => 'int(1)',
    'extra' => ['NULL']
  ],
  'isFactoring' => [
    'type' => 'int(1)',
    'extra' => ['NULL']
  ],
]);
// if($demo){
//   $phpDB->insert("INSERT INTO organizations_meta (owner,relations) VALUES (?,?)", ["",""]);
// }
?>
<p>Installation Complete!</p>
