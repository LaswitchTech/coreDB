<?php

//Import BaseCommand class into the global namespace
use LaswitchTech\phpCLI\BaseCommand;

//Import Database class into the global namespace
use LaswitchTech\phpDB\Database;

//Import phpSMTP class into the global namespace
use LaswitchTech\SMTP\phpSMTP;

class InstallerCommand extends BaseCommand {

  public function installAction($argv){

    // Init Config
    $config = [];

    // Check if already installed
    if($this->isInstalled()){
      $answer = $this->input('Do you want to reinstall?',['Y','N'],'N');
      if(strtoupper($answer) == 'Y'){
        $this->uninstallAction($argv);
      }
      $this->output('');
    }

    // Introduction
    $this->info('============================================================================');
    $this->info('   Welcome to '.COREDB_BRAND.' Installer');
    $this->info('============================================================================');

    // Setup Composer
    $testComposer = false;
    $this->output('');
    $this->info("Installing Composer & Dependencies");
    if($this->composer()){
      $this->success("Composer & Dependencies installed");
      $testComposer = true;
    } else {
      $this->success("Unable to complete installation");
    }

    // Setup SQL Server
    if($testComposer){
      $this->output('');
      $this->output("Let's start by configuring the SQL Server");
      $requestSQL = function(){
        $conf = [];
        $conf['host'] = $this->input('What is the host?', 'localhost');
        $conf['database'] = $this->input('What is the database name?', COREDB_BRAND);
        $conf['username'] = $this->input('What is the username for the server?');
        $conf['password'] = $this->input('What is the password for the server?');
        return $conf;
      };
      $testSQL = false;
      do {
        $config['sql'] = $requestSQL();
        $this->info("Testing the connection");
        $phpDB = new Database($config['sql']['host'],$config['sql']['username'],$config['sql']['password'],$config['sql']['database']);
        if($phpDB->isConnected()){
          $this->success("Connection established");
          $testSQL = true;
        } else {
          $this->error("Unable to establish a connection");
        }
      } while (!$testSQL);

      // Setup SMTP Server
      $this->output('');
      $this->output("Let's configure a SMTP Server");
      $requestSMTP = function(){
        $conf = [];
        $conf['host'] = $this->input('What is the host?', 'localhost');
        $conf['encryption'] = $this->input('What is the security of the server?',['NONE','SSL','STARTTLS'],'SSL');
        switch(strtoupper($conf['encryption'])){
          case"NONE": $port = 25; break;
          case"SSL": $port = 465; break;
          case"STARTTLS": $port = 587; break;
        }
        $conf['port'] = intval($this->input('What is the port for the server?',"$port"));
        $conf['username'] = $this->input('What is the username for the server?');
        $conf['password'] = $this->input('What is the password for the server?');
        return $conf;
      };
      $testSMTP = false;
      do {
        $config['smtp'] = $requestSMTP();
        $this->info("Testing the connection");
        $phpSMTP = new phpSMTP();
        $phpSMTP->connect([
          "host" => $config['smtp']['host'],
          "port" => $config['smtp']['port'],
          "encryption" => $config['smtp']['encryption'],
          "username" => $config['smtp']['username'],
          "password" => $config['smtp']['password'],
        ]);
        if($phpSMTP->isConnected()){
          $this->success("Connection established");
          $testSMTP = true;
        } else {
          $this->error("Unable to establish a connection");
        }
      } while (!$testSMTP);

      // Setup Administrator & CLI Token
      $testAdmin = false;
      $this->output('');
      $this->output("Let's configure the administrator");
      if($testSQL && $testSMTP){
        $config['administrator'] = $this->input('What is the email address?');
        $config['token'] = $this->hex(16);
        $testAdmin = true;
      }

      // Save Configurations
      $testConfig = false;
      $this->output('');
      if($testAdmin){
        $this->info("Saving configurations");
        if($this->configure($config)){
          $this->success("Configurations saved");
          $testConfig = true;
        } else {
          $this->error("Unable to save configurations");
        }
      } else {
        $this->error("Internal server error");
      }

      // Build Database
      if($testConfig){
        $this->output('');
        $this->info("Building database");
        foreach($this->tables() as $table => $structure){
          if($phpDB->create($table,$structure)){
            $this->output("Table [" . $table . "] created");
          } else {
            $this->error("Unable to create the table [" . $table . "]");
          }
        }

        // Insert Main Records
        $this->output('');
        $this->info("Inserting main records");
        // Permissions
        foreach($this->permissions() as $permission => $roles){
          $phpDB->insert("INSERT INTO permissions (name) VALUES (?)", [$permission]);
        }
        // Users
        // CLI User
        $CLIID = $phpDB->insert("INSERT INTO users (username, token) VALUES (?,?)", ["cli",$config['token']]);
        // 1st User
        $password = $this->hex(6);
        $UserID = $phpDB->insert("INSERT INTO users (username, password) VALUES (?,?)", [$config['administrator'],password_hash($password, PASSWORD_DEFAULT)]);
        // Roles
        // users
        $values = [];
        $name = "users";
        foreach($this->permissions() as $permission => $roles){
          if(in_array($name,$roles)){
            $values[$permission] = 1;
          }
        }
        $RoleID = $phpDB->insert("INSERT INTO roles (name, permissions, members) VALUES (?,?,?)", [$name,json_encode($values,JSON_UNESCAPED_SLASHES),json_encode([],JSON_UNESCAPED_SLASHES)]);
        // administrators
        $values = [];
        $name = "administrators";
        foreach($this->permissions() as $permission => $roles){
          if(in_array($name,$roles)){
            $values[$permission] = 4;
          }
        }
        $RoleID = $phpDB->insert("INSERT INTO roles (name, permissions, members) VALUES (?,?,?)", [$name,json_encode($values,JSON_UNESCAPED_SLASHES),json_encode([["users" => $UserID],["users" => $CLIID]],JSON_UNESCAPED_SLASHES)]);
        // Update users
        $phpDB->update("UPDATE users SET roles = ? WHERE id = ?", [json_encode([["roles" => $RoleID]],JSON_UNESCAPED_SLASHES),$UserID]);
        $phpDB->update("UPDATE users SET roles = ? WHERE id = ?", [json_encode([["roles" => $RoleID]],JSON_UNESCAPED_SLASHES),$CLIID]);
        $this->success("Records inserted");

        // Insert Demo Data
        $this->output('');
        $answer = $this->input('Do you want to insert demo data?',['Y','N'],'Y');
        if(strtoupper($answer) == 'Y'){
          $this->info("Inserting demo records");
          // Notifications
          $phpDB->insert("INSERT INTO notifications (content, route, userID) VALUES (?,?,?)", ["There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...","/hello",$UserID]);
          $phpDB->insert("INSERT INTO notifications (content, route, userID) VALUES (?,?,?)", ["There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...","/hello",$UserID]);
          $phpDB->insert("INSERT INTO notifications (content, route, userID) VALUES (?,?,?)", ["There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...","/hello",$UserID]);
          $phpDB->insert("INSERT INTO notifications (content, route, userID) VALUES (?,?,?)", ["There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...","/hello",$UserID]);
          $phpDB->insert("INSERT INTO notifications (content, route, userID) VALUES (?,?,?)", ["There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...","/hello",$UserID]);
          // Activities
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
          // Widgets
          $phpDB->insert("INSERT INTO widgets (name,element,callback) VALUES (?,?,?)", ['box-primary','<div class="py-4 rounded shadow border bg-primary"></div>','function callback(object){ console.log("box-primary",object); }']);
          $phpDB->insert("INSERT INTO widgets (name,element,callback) VALUES (?,?,?)", ['box-secondary','<div class="py-4 rounded shadow border bg-secondary"></div>','function callback(object){ console.log("box-secondary",object); }']);
          $phpDB->insert("INSERT INTO widgets (name,element,callback) VALUES (?,?,?)", ['box-success','<div class="py-4 rounded shadow border bg-success"></div>','function callback(object){ console.log("box-success",object); }']);
          $phpDB->insert("INSERT INTO widgets (name,element,callback) VALUES (?,?,?)", ['box-danger','<div class="py-4 rounded shadow border bg-danger"></div>','function callback(object){ console.log("box-danger",object); }']);
          $phpDB->insert("INSERT INTO widgets (name,element,callback) VALUES (?,?,?)", ['box-warning','<div class="py-4 rounded shadow border bg-warning"></div>','function callback(object){ console.log("box-warning",object); }']);
          $phpDB->insert("INSERT INTO widgets (name,element,callback) VALUES (?,?,?)", ['box-info','<div class="py-4 rounded shadow border bg-info"></div>','function callback(object){ console.log("box-info",object); }']);
          $phpDB->insert("INSERT INTO widgets (name,element,callback) VALUES (?,?,?)", ['box-light','<div class="py-4 rounded shadow border bg-light"></div>','function callback(object){ console.log("box-light",object); }']);
          $phpDB->insert("INSERT INTO widgets (name,element,callback) VALUES (?,?,?)", ['box-dark','<div class="py-4 rounded shadow border bg-dark"></div>','function callback(object){ console.log("box-dark",object); }']);
          // Dashboard
          $phpDB->insert("INSERT INTO dashboards (owner,layout) VALUES (?,?)", [json_encode(['users' => $UserID],JSON_UNESCAPED_SLASHES),'[{"row-cols-4":[{"col":["box-secondary"]},{"col":["box-secondary"]},{"col":["box-secondary"]},{"col":["box-secondary"]},{"col-12":["box-secondary"]}]},{"row-cols-3":[{"col-8":["box-secondary"]},{"col":["box-secondary"]}]}]']);
          $this->success("Records inserted");
        }

        // Notify Administrator
        $this->output('');
        $this->info("Notifying administrator");
        if($phpSMTP->send([
          "TO" => $config['administrator'],
          "SUBJECT" => "Account Activation",
          "TITLE" => "Account Activation",
          "MESSAGE" => "Your account has been created. Here is your password: ".$password,
        ])){
          $this->success("Notification sent");

          // Complete
          $this->output('');
          $this->success("Installation complete");
        } else {
          $this->error("Unable to send email to administrator");
        }
      }
    }
  }

  public function uninstallAction($argv){

    // Introduction
    $this->output('');
    $this->info('============================================================================');
    $this->info('   '.COREDB_BRAND.' Uninstaller');
    $this->info('============================================================================');

    // Start Uninstaller
    $this->output('');
    $this->info('Looking for configurations');
    if(is_file($this->Path . '/config/config.json')){

      // Load configuration file
      $config = json_decode(file_get_contents($this->Path . '/config/config.json'),true);

      // Removing configuration file
      $this->output('');
      $this->info('Removing configurations');
      unlink($this->Path . '/config/config.json');
      $this->success("Configurations removed");

      // Connect to database for cleanup
      $phpDB = new Database($config['sql']['host'],$config['sql']['username'],$config['sql']['password'],$config['sql']['database']);
      if($phpDB->isConnected()){

        // Remove tables
        $this->output('');
        $this->info("Clearing Database");
        foreach($this->tables() as $table => $structure){
          $phpDB->drop($table);
          if($phpDB->drop($table)){
            $this->output("Table [" . $table . "] dropped");
          } else {
            $this->error("Unable to drop the table [" . $table . "]");
          }
        }

        // Complete
        $this->output('');
        $this->success("Uninstalled");
      } else {
        $this->error("Unable to establish a connection");
      }
    } else {
      $this->error("Unable to find configurations");
    }
  }

  protected function isInstalled(){
    return (is_file($this->Path . '/config/config.json'));
  }

  protected function hex($length = 16){
    return bin2hex(openssl_random_pseudo_bytes($length));
  }

  protected function permissions(){
    return [
      "isAdministrator" => ["administrators"],
      "permission/list" => ["administrators"],
      "role/add" => ["administrators"],
      "role/list" => ["administrators"],
      "role/get" => ["administrators"],
      "role/edit" => ["administrators"],
      "role/delete" => ["administrators"],
      "user/add" => ["administrators"],
      "user/list" => ["administrators"],
      "user/get" => ["administrators"],
      "user/edit" => ["administrators"],
      "user/delete" => ["administrators"],
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
  }

  protected function tables(){
    return [
      'users' => [
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
          'extra' => ['NULL']
        ],
        'token' => [
          'type' => 'VARCHAR(100)',
          'extra' => ['NULL','UNIQUE']
        ],
        'isActive' => [
          'type' => 'INT(1)',
          'extra' => ['NOT NULL','DEFAULT "0"']
        ]
      ],
      'relationships' => [
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
      ],
      'permissions' => [
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
      ],
      'roles' => [
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
        ],
      ],
      'sessions' => [
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
      ],
      'notifications' => [
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
      ],
      'activities' => [
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
      ],
      'dashboards' => [
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
      ],
      'widgets' => [
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
      ],
      'organizations' => [
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
      ],
    ];
  }

  protected function composer($dependency = null){
    if($this->composerInstall()){
      $composer = $this->Path . '/composer';
      if(is_file($composer)){
        try {
          $program = file_get_contents($composer);
          if($dependency == null){
            eval("?>" . "$program" . " update");
          } else {
            eval("?>" . "$program" . " require " . $dependency);
          }
          return true;
        } catch (Error $e) {
          $this->error($e->getMessage().'Internal error');
        }
      }
    }
    return false;
  }

  protected function composerInstall(){

    if(!is_file($composer)){

      // Introduction
      $this->info('============================================================================');
      $this->info('   Composer Installer');
      $this->info('============================================================================');

      // Download Installer
      $this->output('');
      $this->info("Downloading installer");
      $installer = $this->Path . '/composer-setup.php';
      $phar = $this->Path . '/composer.phar';
      $composer = $this->Path . '/composer';
      $checksum = copy("https://composer.github.io/installer.sig", "php://stdout");
      copy('https://getcomposer.org/installer', $installer);
      if(is_file($installer)){
        $this->success("Installer downloaded");

        // Verify Installer
        $this->output('');
        $this->info("Verifying installer");
        if(hash_file('sha384', $installer) === $checksum){
          $this->success("Installer verified");

          // Execute Installer
          $this->output('');
          $this->info("Install Composer");
          try {
            $program = file_get_contents($installer);
            eval("?>" . "$program");

            // Cleanup Installer
            if(is_file($installer)){ unlink($installer); }

            // Rename Composer
            copy($phar, $composer);
            if(is_file($phar)){ unlink($phar); }

            // Complete
            $this->success("Composer installed");
          } catch (Error $e) {
            $this->error($e->getMessage().'Internal error');
            if(is_file($installer)){ unlink($installer); }
            if(is_file($phar)){ unlink($phar); }
          }
        } else {
          $this->error("Installer corrupted");
          if(is_file($installer)){ unlink($installer); }
        }
      } else {
        $this->error("Unable to download installer");
        if(is_file($installer)){ unlink($installer); }
      }
    }

    // Return
    return is_file($composer);
  }
}
