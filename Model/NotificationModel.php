<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

class NotificationModel extends BaseModel {

  public function getNotifications($username, $limit = null){
    if($limit != null){
      $limit = intval($limit);
      return $this->select("SELECT * FROM notifications WHERE (created >= ? AND username = ?) OR (isRead = ? AND username = ?) ORDER BY id ASC LIMIT ?", [date('Y-m-d H:i:s', strtotime('-5 days', strtotime(date("Y-m-d H:i:s")))),$username,0,$username,$limit]);
    } else {
      return $this->select("SELECT * FROM notifications WHERE (created >= ? AND username = ?) OR (isRead = ? AND username = ?) ORDER BY id ASC", [date('Y-m-d H:i:s', strtotime('-5 days', strtotime(date("Y-m-d H:i:s")))),$username,0,$username]);
    }
  }

  public function readNotification($notificationID, $username){
    return $this->update("UPDATE notifications SET isRead = ? WHERE id = ? AND username = ?", [1, $notificationID, $username]);
  }

  public function readNotifications($username){
    return $this->update("UPDATE notifications SET isRead = ? WHERE isRead = ? AND username = ?", [1, 0, $username]);
  }

  public function addNotification($username, $content, $route = null){
    return $this->insert("INSERT INTO notifications (content, route, username) VALUES (?,?,?)", [$content, $route, $username]);
  }
}
