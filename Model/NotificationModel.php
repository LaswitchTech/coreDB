<?php

//Import BaseModel class into the global namespace
use LaswitchTech\phpAPI\BaseModel;

class NotificationModel extends BaseModel {
  public function getNotifications($userID) {
    return $this->select("SELECT * FROM notifications WHERE created >= ? AND userID = ? ORDER BY id ASC", [date('Y-m-d H:i:s', strtotime('-5 days', strtotime(date("Y-m-d H:i:s")))),$userID]);
  }
  public function readNotifications($notificationID, $userID) {
    return $this->update("UPDATE notifications SET isRead = ? WHERE id = ? AND userID = ?", [1, $notificationID, $userID]);
  }
}
