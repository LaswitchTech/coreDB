<?php

// Database Configuration Information
define("DB_DEBUG", false);

// Auth Configuration Information
// define("AUTH_F_TYPE", "BEARER");
define("AUTH_B_TYPE", "SQL");
define("AUTH_ROLES", true);
define("AUTH_GROUPS", false);
// define("AUTH_RETURN", "HEADER");
// define("AUTH_OUTPUT_TYPE", "HEADER");

// Router Configuration Information
define('ROUTER_ROUTES',[
  "404" => ["view" => "View/404.php", "label" => "404 - Not Found"],
  "/" => ["view" => "View/index.php", "template" => "Template/index.php", "public" => false, "error" => "/signin", "label" => "Dashboard"],
  "/profile" => ["view" => "View/profile.php", "template" => "Template/index.php", "public" => false, "error" => "/signin", "label" => "Profile"],
  "/settings" => ["view" => "View/settings.php", "template" => "Template/index.php", "public" => false, "error" => "/signin", "label" => "Settings"],
  "/info" => ["view" => "View/info.php", "label" => "Information"],
  "/install" => ["view" => "View/install.php", "label" => "Installation"],
  "/signin" => ["view" => "View/signin.php", "template" => "Template/full.php", "error" => "/", "label" => "Sign In"],
  "/register" => ["view" => "View/register.php", "template" => "Template/full.php", "error" => "/", "label" => "Register"],
  "/forgot" => ["view" => "View/forgot.php", "template" => "Template/full.php", "error" => "/", "label" => "Forgot"],
  "/hello" => ["view" => "View/hello.php", "template" => "Template/index.php", "public" => false, "error" => "/signin", "label" => "Hello World"],
]);
define("ROUTER_REQUIREMENTS", ["APACHE" => ["mod_rewrite"]]);

// coreDB Configuration Information
define("COREDB_BRAND", "coreDB"); //Default is coreDB
define("COREDB_BREADCRUMBS_TYPE", "HISTORY"); //HISTORY and HIERARCHY // Default is HISTORY
define("COREDB_BREADCRUMBS_COUNT", 5); //Default is 5
define("COREDB_ICONS", [
  "/" => "speedometer2",
  "/settings" => "gear",
  "/profile" => "person-circle",
]);
define("COREDB_NAVBAR", [
  "/hello" => [
    ["label" => "Home", "route" => "/"],
    ["label" => "Debug", "icon" => "bug", "menu" => [
      ["label" => "Sign In", "route" => "/signin"],
      ["label" => "Forgot", "route" => "/forgot"],
      ["label" => "Register", "route" => "/register"],
      ["label" => "Install", "route" => "/install"],
      ["label" => "Info", "route" => "/info"],
      ["label" => "Hello World", "route" => "/hello"],
      ["label" => "404", "route" => "404"],
    ]],
  ],
]);
define("COREDB_SIDEBAR", [
  ["label" => "Dashboard", "route" => "/", "icon" => "speedometer2"],
  ["label" => "Debug", "icon" => "bug", "menu" => [
    ["label" => "Sign In", "route" => "/signin"],
    ["label" => "Forgot", "route" => "/forgot"],
    ["label" => "Register", "route" => "/register"],
    ["label" => "Install", "route" => "/install"],
    ["label" => "Info", "route" => "/info"],
    ["label" => "Hello World", "route" => "/hello"],
    ["label" => "404", "route" => "404"],
  ]],
]);
