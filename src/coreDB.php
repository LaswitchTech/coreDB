<?php

//Declaring namespace
namespace LaswitchTech\coreDB;

//Import stdClass & DOMDocument classes into the global namespace
use \stdClass;
use \DOMDocument;

class coreDB {

  protected $Sidebar = [];
  protected $Navbar = [];
  protected $Route = null;
  protected $Routes = [];
  protected $Path = null;
  protected $Icons = [];
  protected $IconList = [];
  protected $Brand = 'coreDB';
  protected $Breadcrumb = ["type" => "HISTORY", "count" => 5];
  protected $Breadcrumbs = [];
  protected $Version = null;
  protected $Versions = [];
  protected $Configurator = null;
  protected $Auth = null;

  public function __construct($route,$routes,$configurator,$auth){
    $this->Auth = $auth;
    $this->Configurator = $configurator;
    $this->Route = $route;
    $this->Routes = $routes;
    if(!defined("ROOT_PATH")){ define("ROOT_PATH",dirname(__DIR__)); }
    $this->Path = ROOT_PATH;
    $this->setVersions();
    $this->setBrand();
    $this->setIcons();
    $this->setNavbar();
    $this->setSidebar();
    $this->setBreadcrumbs();
  }

  public function __call($name, $arguments) {
    return [ "error" => "[".$name."] 501 Not Implemented" ];
  }

  protected function setVersions(){
    $this->Version = file_get_contents($this->Path.'/VERSION',true);
    $this->Versions['phpAPI'] = file_get_contents($this->Path.'/vendor/laswitchtech/php-api/VERSION',true);
    $this->Versions['phpAuth'] = file_get_contents($this->Path.'/vendor/laswitchtech/php-auth/VERSION',true);
    $this->Versions['phpDB'] = file_get_contents($this->Path.'/vendor/laswitchtech/php-database/VERSION',true);
    $this->Versions['phpRouter'] = file_get_contents($this->Path.'/vendor/laswitchtech/php-router/VERSION',true);
    $this->Versions['phpCLI'] = file_get_contents($this->Path.'/vendor/laswitchtech/php-cli/VERSION',true);
    $this->Versions['phpSMTP'] = file_get_contents($this->Path.'/vendor/laswitchtech/php-smtp/VERSION',true);
    $this->Versions['phpIMAP'] = file_get_contents($this->Path.'/vendor/laswitchtech/php-imap/VERSION',true);
    $this->Versions['phpCSRF'] = file_get_contents($this->Path.'/vendor/laswitchtech/php-csrf/VERSION',true);
  }

  protected function setBrand(){
    if(defined('COREDB_BRAND') && is_string(COREDB_BRAND)){ $this->Brand = COREDB_BRAND; }
  }

  protected function setIcons(){
    foreach(scandir($this->Path . "/vendor/twbs/bootstrap-icons/icons") as $key => $name){
      if(!in_array($name,['.','..','.DS_Store'])){ $this->IconList[] = str_replace('.svg','',$name); }
    }
    if(defined('ROUTER_ROUTES') && is_array(ROUTER_ROUTES)){
      foreach(ROUTER_ROUTES as $route => $param){
        if(isset($param['icon'])){
          $this->addIcon($route, $param['icon']);
        }
      }
    }
  }

  protected function setNavbar(){
    if(defined('COREDB_NAVBAR') && is_array(COREDB_NAVBAR)){
      foreach(COREDB_NAVBAR as $uri => $items){
        foreach($items as $item){
          if(isset($item['label'])){
            $icon = null;
            $menu = [];
            $route = null;
            $id = null;
            if(isset($item['icon']) && is_string($item['icon'])){ $icon = $item['icon']; }
            if(isset($item['menu']) && is_array($item['menu'])){ $menu = $item['menu']; }
            if(isset($item['route']) && is_string($item['route'])){ $route = $item['route']; }
            if(isset($item['id']) && is_string($item['id'])){ $id = $item['id']; }
            $this->addNavbarItem($uri, $item['label'], $route, $icon, $menu, $id);
          }
        }
      }
    }
  }

  protected function setSidebar(){
    if(defined('COREDB_SIDEBAR') && is_array(COREDB_SIDEBAR)){
      foreach(COREDB_SIDEBAR as $item){
        if(isset($item['label'])){
          $icon = 'circle';
          $menu = [];
          $route = null;
          if(isset($item['icon']) && is_string($item['icon'])){ $icon = $item['icon']; }
          if(isset($item['menu']) && is_array($item['menu'])){ $menu = $item['menu']; }
          if(isset($item['route']) && is_string($item['route'])){ $route = $item['route']; }
          $this->addSidebarItem($item['label'], $route, $icon, $menu);
        }
      }
    }
  }

  protected function setBreadcrumbs(){
    if(defined('COREDB_BREADCRUMBS_TYPE') && is_string(COREDB_BREADCRUMBS_TYPE) && in_array(strtoupper(COREDB_BREADCRUMBS_TYPE),['HISTORY','HIERARCHY'])){ $this->Breadcrumb['type'] = strtoupper(COREDB_BREADCRUMBS_TYPE); }
    if(defined('COREDB_BREADCRUMBS_COUNT') && is_int(COREDB_BREADCRUMBS_COUNT)){ $this->Breadcrumb['count'] = COREDB_BREADCRUMBS_COUNT; }
    if(isset($_COOKIE['breadcrumbs'])){ $this->Breadcrumbs = json_decode($_COOKIE['breadcrumbs'],true); }
    if(!in_array($this->Route,['500','403','404'])){
      // Create Breadcrumb
      if(count($this->Breadcrumbs) < 1 || end($this->Breadcrumbs)['route'] != $_SERVER['REQUEST_URI']){
        $this->Breadcrumbs[] = ["route" => $_SERVER['REQUEST_URI'], "label" => $this->Routes[$this->Route]['label']];
      }
      $this->Breadcrumbs = array_slice($this->Breadcrumbs,0 - $this->Breadcrumb['count'],$this->Breadcrumb['count']);
      // Create/Update Cookie
      $this->Auth->setCookie( "breadcrumbs", json_encode($this->Breadcrumbs), ['samesite' => 'None'] );
    }
  }

  protected function isAssoc(array $arr){
    if (array() === $arr) return false;
    return array_keys($arr) !== range(0, count($arr) - 1);
  }

  public function getGravatar( $email, $s = 200, $d = 'mp', $r = 'g', $img = false, $atts = array() ) {
		$url = 'https://www.gravatar.com/avatar/';
		$url .= md5( strtolower( trim( $email ) ) );
		$url .= "?s=$s&d=$d&r=$r";
		if ( $img ) {
			$url = '<img src="' . $url . '"';
			foreach ( $atts as $key => $val )
				$url .= ' ' . $key . '="' . $val . '"';
			$url .= ' />';
		}
		return $url;
	}

  public function getFavicon($url){
    $parsed = parse_url($url);
    if(!isset($parsed['host'])){
      $parsed = parse_url('http://'.$url);
    }
    $domain = $parsed['host'];
    $save_file_path = $this->Path . '/tmp/favicons/' . $domain;
    if(!file_exists ($save_file_path)) {
      mkdir($save_file_path, 0777, true);
    }
    $filepath = $save_file_path . '/favicon.png';
    if (!file_exists ($filepath)) {
      file_put_contents ($filepath, file_get_contents ('https://www.google.com/s2/favicons?sz=256&domain=' . $domain));
    }
    return $filepath;
  }

  public function getTimeago($time){
    if(is_numeric($time)){ $time_difference = time() - $time; } else { $time_difference = time() - strtotime($time); }
    if( $time_difference < 1 ) { return 'less than 1 second ago'; }
    $condition = array( 12 * 30 * 24 * 60 * 60 =>  'year',
      30 * 24 * 60 * 60       =>  'month',
      24 * 60 * 60            =>  'day',
      60 * 60                 =>  'hour',
      60                      =>  'minute',
      1                       =>  'second'
    );
    foreach( $condition as $secs => $str ){
      $d = $time_difference / $secs;
      if( $d >= 1 ){
        $t = round( $d );
        return 'about ' . $t . ' ' . $str . ( $t > 1 ? 's' : '' ) . ' ago';
      }
    }
  }

  public function getFiles($path, $exceptions = []){
    $files = [];
    if(substr($path, 0, 1) !== '/') { $path = '/'.$path; }
    foreach(scandir($this->Path . $path) as $key => $name){
      if(!in_array($name,['.','..','.DS_Store']) && !in_array($name,$exceptions)){ $files[] = $name; }
    }
    return $files;
  }

  public function getBrand(){ return $this->Brand; }

  public function getVersion($component = null){
    if($component != null && is_string($component) && isset($this->Versions[$component])){
      return $this->Versions[$component];
    }
    return $this->Version;
  }

  protected function addIcon($route, $icon){
    if(!isset($this->Icons[$route]) && in_array($icon,$this->IconList)){
      $this->Icons[$route] = $icon;
    }
  }

  public function getIcon($route = null){
    if($route == null){ $route = $this->Route; }
    if(isset($this->Icons[$route])){ return "bi-".$this->Icons[$route]; } else { return "bi-circle"; }
  }

  public function getCSS($filters = []){
    $files = [];
    $html = '';
    $files = [];
    $files[] = "BSPanel.css";
    $skip = $files;
    $skip[] = "coreDB.css";
    foreach($this->getFiles('/dist/css/',$skip) as $file){
      if(!is_dir($this->Path . '/dist/css/' . $file)){
        $files[] = $file;
      }
    }
    $files[] = "coreDB.css";
    foreach($files as $file){
      if(!str_ends_with($file,'.map') && !str_ends_with($file,'.bak')){
        if(!in_array($file,$filters)){
          $html .= '<link rel="stylesheet" href="/css/' . $file . '">';
        }
      }
    }
    return $html;
  }

  public function getJS($part = 'head', $filters = []){
    $files = [];
    $html = '';
    $skip = [];
    if(in_array(strtoupper($part),['HEAD','BODY'])){
      switch(strtoupper($part)){
        case"HEAD":
          $files[] = "jquery.min.js";
          $files[] = "jquery-ui.min.js";
          $files[] = "bootstrap.bundle.min.js";
          $files[] = "jquery.dataTables.min.js";
          $files[] = "dataTables.bootstrap5.min.js";
          $files[] = "prism.js";
          $skip = $files;
          $skip[] = "BSPanel.js";
          $skip[] = "phpAPI.js";
          $skip[] = "cookie.js";
          $skip[] = "coreDB.js";
          foreach($this->getFiles('/dist/js/',$skip) as $file){
            if(!is_dir($this->Path . '/dist/js/' . $file)){
              if(!in_array($file,$files)){
                $files[] = $file;
              }
            }
          }
          break;
        case"BODY":
          $files[] = "BSPanel.js";
          $files[] = "phpAPI.js";
          $files[] = "cookie.js";
          $files[] = "coreDB.js";
          $html .= '<script> const CSRF = "' . $this->Auth->CSRF->token() . '"; </script>';
          $html .= '<script> const Username = "' . $this->Auth->getUser('username') . '"; </script>';
          break;
      }
      foreach($files as $file){
        if(!in_array($file,$filters)){
          if(!str_ends_with($file,'.map') && !str_ends_with($file,'.bak')){
            $html .= '<script src="/js/' . $file . '"></script>';
          }
        }
      }
      switch(strtoupper($part)){
        case"HEAD":
          $html .= '<script> $.holdReady(true); </script>';
          break;
        case"BODY":
          break;
      }
    }
    return $html;
  }

  protected function addSidebarItem($label, $route = null, $icon = 'circle', $menu = []){
    if(is_string($label) && ($route == null || is_string($route))){
      $item['route'] = $route;
      $item['label'] = $label;
      $item['active'] = false;
      if($route == $this->Route){ $item['active'] = true; }
      if(!is_string($icon) || !in_array($icon,$this->IconList)){ $icon = 'circle'; }
      $item['icon'] = 'bi-'.$icon;
      $item['menu'] = [];
      if(!is_array($menu) || $this->isAssoc($menu)){ $menu = []; }
      foreach($menu as $key => $parameters){
        if($this->isAssoc($parameters) && isset($parameters['route'],$parameters['label'])){
          $subitem['active'] = false;
          if($parameters['route'] == $this->Route){ $subitem['active'] = true;$item['active'] = true; }
          $subitem['route'] = $parameters['route'];
          $subitem['label'] = $parameters['label'];
          $subitem['icon'] = 'circle';
          if(isset($parameters['icon']) && in_array($icon,$this->IconList)){ $subitem['icon'] = $parameters['icon']; }
          $subitem['icon'] = 'bi-'.$subitem['icon'];
          $item['menu'][] = $subitem;
        }
      }
      $this->Sidebar[] = $item;
    }
  }

  protected function addNavbarItem($uri, $label, $route = null, $icon = null, $menu = [], $id = null){
    if(is_string($uri) && is_string($label) && ($route == null || is_string($route))){
      if(!isset($this->Navbar[$uri])){ $this->Navbar[$uri] = []; }
      $item['route'] = $route;
      $item['label'] = $label;
      $item['id'] = $id;
      $item['active'] = false;
      if($route == $this->Route){ $item['active'] = true; }
      if(!is_string($icon) || !in_array($icon,$this->IconList)){ $icon = null; }
      if($icon != null){ $item['icon'] = 'bi-'.$icon; } else { $item['icon'] = $icon; }
      $item['menu'] = [];
      if(!is_array($menu) || $this->isAssoc($menu)){ $menu = []; }
      foreach($menu as $key => $parameters){
        if($this->isAssoc($parameters) && isset($parameters['route'],$parameters['label'])){
          $subitem['active'] = false;
          if($parameters['route'] == $this->Route){ $subitem['active'] = true;$item['active'] = true; }
          $subitem['route'] = $parameters['route'];
          $subitem['label'] = $parameters['label'];
          $subitem['icon'] = null;
          if(isset($parameters['icon']) && in_array($icon,$this->IconList)){ $subitem['icon'] = $parameters['icon']; }
          if($subitem['icon'] != null){ $subitem['icon'] = 'bi-'.$subitem['icon']; }
          $item['menu'][] = $subitem;
        }
      }
      $this->Navbar[$uri][] = $item;
    }
  }

  public function getBreadcrumbs(){ return $this->Breadcrumbs; }

  public function getBack($field = null){
    $back = array_slice($this->Breadcrumbs,-2,1)[0];
    if($field != null && is_string($field) && isset($back[$field])){ return $back[$field]; }
    return $back;
  }

  public function getSidebar(){ return $this->Sidebar; }

  public function getNavbar(){
    $navs = [];
    if(isset($this->Navbar[$this->Route])){
      $navs = $this->Navbar[$this->Route];
    }
    if(isset($this->Navbar['*'])){
      $navs = array_merge($navs,$this->Navbar['*']);
    }
    return $navs;
  }
}
