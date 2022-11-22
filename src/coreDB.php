<?php

//Declaring namespace
namespace LaswitchTech\coreDB;

//Import stdClass & DOMDocument classes into the global namespace
use \stdClass;
use \DOMDocument;

//Import Factory class into the global namespace
use Composer\Factory;

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
  protected $Version = '0.0.0';

  public function __construct($route,$routes){
    $this->Route = $route;
    $this->Routes = $routes;
    $this->Path = dirname(\Composer\Factory::getComposerFile());
    $this->Version = file_get_contents($this->Path.'/VERSION',true);
    foreach(scandir($this->Path . "/vendor/twbs/bootstrap-icons/icons") as $key => $name){
      if(!in_array($name,['.','..'])){ $this->IconList[] = str_replace('.svg','',$name); }
    }
    $this->setBrand();
    $this->setIcons();
    $this->setNavbar();
    $this->setSidebar();
    $this->setBreadcrumbs();
  }

  protected function setBrand(){
    if(defined('COREDB_BRAND') && is_string(COREDB_BRAND)){ $this->Brand = COREDB_BRAND; }
  }

  protected function setIcons(){
    if(defined('COREDB_ICONS') && is_array(COREDB_ICONS)){
      foreach(COREDB_ICONS as $route => $icon){
        $this->addIcon($route, $icon);
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
            if(isset($item['icon']) && is_string($item['icon'])){ $icon = $item['icon']; }
            if(isset($item['menu']) && is_array($item['menu'])){ $menu = $item['menu']; }
            if(isset($item['route']) && is_string($item['route'])){ $route = $item['route']; }
            $this->addNavbarItem($uri, $item['label'], $route, $icon, $menu);
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
    // Create Breadcrumb
    if(count($this->Breadcrumbs) < 1 || end($this->Breadcrumbs)['route'] != $_SERVER['REQUEST_URI']){
      $this->Breadcrumbs[] = ["route" => $_SERVER['REQUEST_URI'], "label" => $this->Routes[$this->Route]['label']];
    }
    $this->Breadcrumbs = array_slice($this->Breadcrumbs,0 - $this->Breadcrumb['count'],$this->Breadcrumb['count']);
    // Create/Update Cookie
    setcookie( "breadcrumbs", json_encode($this->Breadcrumbs), time() + 86400 );
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

  public function getBrand(){ return $this->Brand; }

  public function getVersion(){ return $this->Version; }

  protected function addIcon($route, $icon){
    if(!isset($this->Icons[$route]) && in_array($icon,$this->IconList)){
      $this->Icons[$route] = $icon;
    }
  }

  public function getIcon($route = null){
    if($route == null){ $route = $this->Route; }
    if(isset($this->Icons[$route])){ return "bi-".$this->Icons[$route]; } else { return "bi-circle"; }
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

  protected function addNavbarItem($uri, $label, $route = null, $icon = null, $menu = []){
    if(is_string($uri) && is_string($label) && ($route == null || is_string($route))){
      if(!isset($this->Navbar[$uri])){ $this->Navbar[$uri] = []; }
      $item['route'] = $route;
      $item['label'] = $label;
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
    if(isset($this->Navbar[$this->Route])){ return $this->Navbar[$this->Route]; } else { return []; }
  }
}
