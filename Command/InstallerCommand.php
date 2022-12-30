<?php

//Import BaseCommand class into the global namespace
use LaswitchTech\phpCLI\BaseCommand;

//Import Database class into the global namespace
use LaswitchTech\phpDB\Database;

//Import phpSMTP class into the global namespace
use LaswitchTech\SMTP\phpSMTP;

//Import Configurator class into the global namespace
use LaswitchTech\coreDB\Configurator;

class InstallerCommand extends BaseCommand {

  protected $Configurator = null;

  public function __construct(){

    // Initiate Configurator
    $this->Configurator = new Configurator();

    // Initiate Parent Constructor
    parent::__construct();
  }

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

    // Install Composer
    $testComposer = false;
    $this->output('');
    $this->info("Installing Composer");
    if($this->composerInstall()){
      $testComposer = true;
    } else {
      $this->error("Unable to complete installation");
    }

    // Setup Dependencies
    $testDependencies = false;
    if($testComposer){
      $this->output('');
      $this->info("Installing Dependencies");
      if($this->composer()){
        $this->success("Dependencies installed");
        $testDependencies = true;
      } else {
        $this->error("Unable to complete installation");
      }
    }

    // Setup SQL Server
    if($testDependencies){
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

      // Setup Initial Host
      $testHost = false;
      if($testSQL && $testSMTP){
        $this->output('');
        $this->output("Let's add a domain");
        $config['domains'] = [];
        $config['domains'][] = $this->input('What is domain of this host?');
        $testHost = true;
      }

      // Setup Administrator & CLI Token
      $testAdmin = false;
      if($testHost){
        $this->output('');
        $this->output("Let's configure the administrator");
        $config['administrator'] = $this->input('What is the email address?');
        $config['token'] = $this->hex(16);
        $testAdmin = true;
      }

      // Load Configurator
      $this->output('');
      $this->info("Load Configurator");
      $this->Configurator->load();
      $this->success("Configurator loaded");

      // Save Configurations
      $testConfig = false;
      if($testAdmin){
        $this->output('');
        $this->info("Saving configurations");
        if($this->Configurator->configure($config)){
          $this->success("Configurations saved");
          $testConfig = true;
        } else {
          $this->error("Unable to save configurations");
        }
      } else {
        $this->error("Internal server error");
      }

      // Reload Configurator
      $this->output('');
      $this->info("Reload Configurator");
      $this->Configurator->load();
      $this->success("Configurator reloaded");

      // $testConfig = false;

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
        $permissionModel = new PermissionModel();
        foreach($this->permissions() as $permission => $roles){
          $permissionModel->addPermission($permission);
        }
        // Users
        $userModel = new UserModel();
        // CLI User
        $CLIID = $userModel->addAPI("cli",$config['administrator']);
        // Administrator User
        $UserID = $userModel->addUser($config['administrator']);
        $userModel->deactivateUser($config['administrator']);
        // Roles
        $roleModel = new RoleModel();
        // users
        $values = [];
        $name = "users";
        foreach($this->permissions() as $permission => $roles){
          if(in_array($name,$roles)){
            $values[$permission] = 1;
          }
        }
        $RoleID = $roleModel->addRole($name,$values);
        $roleModel->setDefault($name);
        // administrators
        $values = [];
        $name = "administrators";
        foreach($this->permissions() as $permission => $roles){
          if(in_array($name,$roles)){
            $values[$permission] = 4;
          }
        }
        $RoleID = $roleModel->addRole($name,$values,[["users" => $UserID],["users" => $CLIID]]);
        // Update users
        $userModel->saveUser(['username' => 'cli', 'roles' => json_encode([["roles" => $RoleID]],JSON_UNESCAPED_SLASHES)]);
        $userModel->saveUser(['username' => $config['administrator'], 'roles' => json_encode([["roles" => $RoleID]],JSON_UNESCAPED_SLASHES)]);
        $this->success("Records inserted");

        // Insert Demo Data
        $this->output('');
        $answer = $this->input('Do you want to insert demo data?',['Y','N'],'Y');
        if(strtoupper($answer) == 'Y'){
          $this->info("Inserting demo records");
          // Notifications
          $notificationModel = new NotificationModel();
          $notificationModel->addNotification($UserID, "There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...", "/hello");
          $notificationModel->addNotification($UserID, "There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...", "/hello");
          $notificationModel->addNotification($UserID, "There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...", "/hello");
          $notificationModel->addNotification($UserID, "There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...", "/hello");
          $notificationModel->addNotification($UserID, "There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...", "/hello");
          // Activities
          $activityModel = new ActivityModel();
          $activityModel->addActivity(['users' => $UserID],[
            "header" => 'Lorem Ipsum',
            "body" => 'There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...',
            "color" => 'primary',
            "callback" => 'function callback(object){ console.log("activity: ",object); }',
            "sharedTo" => [['roles' => $RoleID]],
          ]);
          $activityModel->addActivity(['users' => $UserID],[
            "body" => 'There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...',
            "footer" => '<a class="btn btn-primary btn-sm">Read more</a><a class="btn btn-danger btn-sm">Delete</a>',
            "sharedTo" => [['users' => $UserID],["roles" => $RoleID]],
          ]);
          $activityModel->addActivity(['users' => $UserID],[
            "body" => 'There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...',
            "route" => '/hello',
            "icon" => 'check-lg',
            "color" => 'success',
            "sharedTo" => [['users' => $UserID]],
          ]);
          $activityModel->addActivity(['users' => $UserID],[
            "body" => 'There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...',
            "route" => '/hello',
            "icon" => 'exclamation-triangle',
            "color" => 'warning',
            "sharedTo" => [['users' => $UserID]],
          ]);
          $activityModel->addActivity(['users' => $UserID],[
            "body" => 'There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...',
            "route" => '/hello',
            "icon" => 'exclamation-octagon-fill',
            "color" => 'danger',
            "sharedTo" => [['users' => $UserID]],
          ]);
          // Widgets
          $widgetModel = new WidgetModel();
          $widgetModel->addWidget('box-primary','<div class="py-4 rounded shadow border bg-primary"></div>');
          $widgetModel->addWidget('box-secondary','<div class="py-4 rounded shadow border bg-secondary"></div>','function callback(object){ console.log("box-secondary",object); }');
          $widgetModel->addWidget('box-success','<div class="py-4 rounded shadow border bg-success"></div>');
          $widgetModel->addWidget('box-danger','<div class="py-4 rounded shadow border bg-danger"></div>');
          $widgetModel->addWidget('box-warning','<div class="py-4 rounded shadow border bg-warning"></div>');
          $widgetModel->addWidget('box-info','<div class="py-4 rounded shadow border bg-info"></div>');
          $widgetModel->addWidget('box-light','<div class="py-4 rounded shadow border bg-light"></div>');
          $widgetModel->addWidget('box-dark','<div class="py-4 rounded shadow border bg-dark"></div>');
          // Dashboard
          $dashboardModel = new DashboardModel();
          $dashboardModel->saveDashboard(['users' => $UserID],[
            [
              "row-cols-4" => [
                ["col" => ["box-secondary"]],
                ["col" => ["box-secondary"]],
                ["col" => ["box-secondary"]],
                ["col" => ["box-secondary"]],
                ["col-12" => ["box-secondary"]],
              ],
            ],
            [
              "row-cols-3" => [
                ["col-8" => ["box-secondary"]],
                ["col" => ["box-secondary"]],
              ],
            ],
          ]);
          $this->success("Records inserted");
        }

        // Complete
        $this->output('');
        $this->success("Installation complete");
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
      if(is_file($this->Path . '/config/config.json')){
        $this->output('');
        $this->info('Removing configurations');
        unlink($this->Path . '/config/config.json');
        $this->success("Configurations removed");
      }

      // Removing .htaccess file
      if(is_file($this->Path . '/.htaccess')){
        $this->output('');
        $this->info('Removing .htaccess');
        unlink($this->Path . '/.htaccess');
        $this->success(".htaccess removed");
      }

      // Removing composer file
      if(is_file($this->Path . '/composer')){
        $this->output('');
        $this->info('Removing composer');
        unlink($this->Path . '/composer');
        $this->success("composer removed");
      }

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
      "user/enable" => ["administrators"],
      "user/disable" => ["administrators"],
      "organization/list" => ["administrators"],
      "status/list" => ["administrators","users"],
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
        'name' => [
          'type' => 'VARCHAR(255)',
          'extra' => ['NULL']
        ],
        'organization' => [
          'type' => 'BIGINT(10)',
          'extra' => ['NULL']
        ],
        'status' => [
          'type' => 'INT(1)',
          'extra' => ['NOT NULL','DEFAULT "0"']
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
        ],
        'isAPI' => [
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
        'isDefault' => [
          'type' => 'INT(1)',
          'extra' => ['NOT NULL','DEFAULT "0"']
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
        // Contains Who can see this record
        'sharedTo' => [
          'type' => 'LONGTEXT',
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
          if($dependency == null){
            shell_exec('composer update');
          } else {
            shell_exec('composer require' . $dependency);
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

    $composer = $this->Path . '/composer';

    if(!is_file($composer)){
      $this->info("Downloading installer");
      $installer = $this->Path . '/composer-setup.php';
      $phar = $this->Path . '/composer.phar';
      $checksum = file_get_contents("https://composer.github.io/installer.sig");
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
            shell_exec('php composer-setup.php');

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
