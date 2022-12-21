<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

class ActivityModel extends BaseModel {

  public function getActivities($owner, $limit) {
    return $this->select("SELECT * FROM activities WHERE owner = ? ORDER BY id DESC LIMIT ?", [json_encode($owner, JSON_UNESCAPED_SLASHES),$limit]);
  }

  public function addActivity($owner, $options){
    $header = null;
    $body = null;
    $footer = null;
    $route = null;
    $icon = 'activity';
    $color = 'secondary';
    $callback = null;
    if(isset($options['header'])){ $header = $options['header']; }
    if(isset($options['body'])){ $body = $options['body']; }
    if(isset($options['footer'])){ $footer = $options['footer']; }
    if(isset($options['route'])){ $route = $options['route']; }
    if(isset($options['icon'])){ $icon = $options['icon']; }
    if(isset($options['color'])){ $color = $options['color']; }
    if(isset($options['callback'])){ $callback = $options['callback']; }
    return $this->insert("INSERT INTO activities (header, body, footer, route, owner, icon, color, callback) VALUES (?,?,?,?,?,?,?,?)", [$header, $body, $footer, $route, json_encode($owner,JSON_UNESCAPED_SLASHES), $icon, $color, $callback]);
  }
}
