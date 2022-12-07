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
    'type' => 'INT(1)',
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
  // Carriers, Prospects, Vendors, Etc.
  'type' => [
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
    "role/edit" => ["administrators"],
    "user/list" => ["administrators"],
    "user/get" => ["administrators"],
    "organization/list" => ["administrators"],
    "icon/list" => ["administrators","users"],
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
    "View/test.php" => ["administrators"],
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
    'type' => 'INT(1)',
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
  'owner' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NOT NULL']
  ],
  'header' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NULL']
  ],
  'body' => [
    'type' => 'LONGTEXT',
    'extra' => ['NULL']
  ],
  'footer' => [
    'type' => 'LONGTEXT',
    'extra' => ['NULL']
  ],
  'route' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NULL']
  ],
  'type' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NOT NULL','DEFAULT "activity"']
  ],
  'color' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NULL']
  ],
  'icon' => [
    'type' => 'VARCHAR(255)',
    'extra' => ['NULL']
  ],
  'callback' => [
    'type' => 'LONGTEXT',
    'extra' => ['NULL']
  ]
]);
if($demo){
  $phpDB->insert("INSERT INTO activities (header, body, owner, icon, color, callback) VALUES (?,?,?,?,?,?)", [
    'Lorem Ipsum',
    'There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...',
    json_encode(['users' => $UserID],JSON_UNESCAPED_SLASHES),
    'activity',
    'primary',
    'function callback(object){ console.log("activity: ",object); }'
  ]);
  $phpDB->insert("INSERT INTO activities (body, footer, owner, icon, color, callback) VALUES (?,?,?,?,?,?)", [
    'There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...',
    '<a class="btn btn-primary btn-sm">Read more</a><a class="btn btn-danger btn-sm">Delete</a>',
    json_encode(['users' => $UserID],JSON_UNESCAPED_SLASHES),
    'activity',
    'secondary',
    'function callback(object){ console.log("activity: ",object); }'
  ]);
  $phpDB->insert("INSERT INTO activities (body, owner, route, icon, color, callback) VALUES (?,?,?,?,?,?)", [
    'There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...',
    json_encode(['users' => $UserID],JSON_UNESCAPED_SLASHES),
    '/hello',
    'check-lg',
    'success',
    'function callback(object){ console.log("activity: ",object); }'
  ]);
  $phpDB->insert("INSERT INTO activities (body, owner, icon, color, callback) VALUES (?,?,?,?,?)", [
    'There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...',
    json_encode(['users' => $UserID],JSON_UNESCAPED_SLASHES),
    'exclamation-triangle',
    'warning',
    'function callback(object){ console.log("activity: ",object); }'
  ]);
  $phpDB->insert("INSERT INTO activities (body, owner, icon, color, callback) VALUES (?,?,?,?,?)", [
    'There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...',
    json_encode(['users' => $UserID],JSON_UNESCAPED_SLASHES),
    'exclamation-octagon-fill',
    'danger',
    'function callback(object){ console.log("activity: ",object); }'
  ]);
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
    'type' => 'INT(1)',
    'extra' => ['NULL']
  ],
  // If has CRM Features Access
  'hasCRM' => [
    'type' => 'INT(1)',
    'extra' => ['NULL']
  ],
  // If has Dispatch Features Access
  'hasDispatch' => [
    'type' => 'INT(1)',
    'extra' => ['NULL']
  ],
  // If has Support Features Access
  'hasSupport' => [
    'type' => 'INT(1)',
    'extra' => ['NULL']
  ],
  // If accepted to share information internally
  'isSharedInternally' => [
    'type' => 'INT(1)',
    'extra' => ['NULL']
  ],
  // If accepted to share information with databrokers
  'isSharedDatabrokers' => [
    'type' => 'INT(1)',
    'extra' => ['NULL']
  ],
  // If is shown in prospects
  'isProspect' => [
    'type' => 'INT(1)',
    'extra' => ['NULL']
  ],
  // If is shown in leads
  'isLead' => [
    'type' => 'INT(1)',
    'extra' => ['NULL']
  ],
  // If is shown in clients
  'isClient' => [
    'type' => 'INT(1)',
    'extra' => ['NULL']
  ],
  // If is shown in importers
  'isImporter' => [
    'type' => 'INT(1)',
    'extra' => ['NULL']
  ],
  // If is shown in exporters
  'isExporter' => [
    'type' => 'INT(1)',
    'extra' => ['NULL']
  ],
  // If is shown in vendors
  'isVendor' => [
    'type' => 'INT(1)',
    'extra' => ['NULL']
  ],
  // If is shown in buyers
  'isBuyer' => [
    'type' => 'INT(1)',
    'extra' => ['NULL']
  ],
  // If is shown in producers
  'isProducer' => [
    'type' => 'INT(1)',
    'extra' => ['NULL']
  ],
  // If is shown in shippers
  'isShipper' => [
    'type' => 'INT(1)',
    'extra' => ['NULL']
  ],
  // If is shown in truckers
  'isTrucker' => [
    'type' => 'INT(1)',
    'extra' => ['NULL']
  ],
  // If is shown in shippinglines
  'isShippingLine' => [
    'type' => 'INT(1)',
    'extra' => ['NULL']
  ],
  // If is shown in airlines
  'isAirLine' => [
    'type' => 'INT(1)',
    'extra' => ['NULL']
  ],
  // If is shown in carriers
  'isCarrier' => [
    'type' => 'INT(1)',
    'extra' => ['NULL']
  ],
  // If is shown in freight forwarders
  'isFreightForwarder' => [
    'type' => 'INT(1)',
    'extra' => ['NULL']
  ],
  // If is shown in customs brokers
  'isCustomsBroker' => [
    'type' => 'INT(1)',
    'extra' => ['NULL']
  ],
  // If is shown in wharehouses
  'isWharehouse' => [
    'type' => 'INT(1)',
    'extra' => ['NULL']
  ],
  // If is shown in terminals
  'isTerminal' => [
    'type' => 'INT(1)',
    'extra' => ['NULL']
  ],
  // If is shown in factories
  'isFactory' => [
    'type' => 'INT(1)',
    'extra' => ['NULL']
  ],
]);
// if($demo){
//   $phpDB->insert("INSERT INTO organizations (owner,relations) VALUES (?,?)", ["",""]);
// }
?>
<p>Installation Complete!</p>
