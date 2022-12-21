<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

class NotificationModel extends BaseModel {

  public function getNotifications($userID, $limit){
    return $this->select("SELECT * FROM notifications WHERE (created >= ? AND userID = ?) OR (isRead = ? AND userID = ?) ORDER BY id ASC LIMIT ?", [date('Y-m-d H:i:s', strtotime('-5 days', strtotime(date("Y-m-d H:i:s")))),$userID,0,$userID,$limit]);
  }

  public function readNotification($notificationID, $userID){
    return $this->update("UPDATE notifications SET isRead = ? WHERE id = ? AND userID = ?", [1, $notificationID, $userID]);
  }

  public function addNotification($id, $content, $route = null){
    return $this->insert("INSERT INTO notifications (content, route, userID) VALUES (?,?,?)", [$content, $route, $id]);
  }
}
