<?php

class NotifModel extends Model
{

	public function getNotifications($userID, $limit)
	{
		$sql = "SELECT Notification.*,
                TIMEDIFF(Notification.Time, now()) AS TimeDiff                    
                FROM Notification
				WHERE UserID = :userID";


		$sql .= " ORDER BY Notification.Time DESC";

		if (is_numeric($limit)) {
			$sql .= " LIMIT " . $limit;
		}
        
		$parameters = array(":userID" => $userID);

		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql, $parameters);
	}
    
	public function getJoinedEvents($userID, $hour="", $check="")
	{
		$sql = "SELECT Event.*,
					Tag.Name AS TagName,       
                    TIMEDIFF(Event.Time, now()) AS TimeDiff, 
                    TIMESTAMPDIFF(HOUR,Event.Time, now()) AS HourDiff,
					MINUTE(TIMEDIFF(Event.Time, now())) AS MinDiff 
                    
				FROM Event
				INNER JOIN Participant ON Participant.EventID = Event.EventID
				LEFT JOIN Tag ON Tag.TagID = Event.TagID
				WHERE Participant.UserID = :userID";

		if (is_numeric($hour) && !is_numeric($check)) {
			$sql .= " AND TIMESTAMPDIFF(HOUR,Event.Time, now()) = -" . $hour;
			$sql .= " AND MINUTE(TIMEDIFF(Event.Time, now())) = 0 ";
		}

		$sql .= " ORDER BY Event.Time";

        
		$parameters = array(":userID" => $userID);

		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql, $parameters);
	}

	public function insertNotif($userID, $eventID, $msg, $urlLink, $imgLink) {
		$sql = "INSERT INTO Notification (UserID, EventID, Message, Time, UrlLink, ImgLink)
				VALUES (:userID, :eventID, :msg, now(), :urlLink, :imgLink)";

		$parameters = array(
				":userID" => $userID,
				":eventID" => $eventID,
				":msg" => $msg,
				":urlLink" => $urlLink,
				":imgLink" => $imgLink
		);

		return $GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}
    
	public function updateFlag($notifID) {
		$sql = "UPDATE Notification
				SET Flag = 1
				WHERE Notification.NotificationID = :notifID";

		$parameters = array(
				":notifID" => $notifID
		);

		$GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}

    public function updateNotified($eventID, $userID, $notif) {
		$sql = "UPDATE Participation ";

        $sql .= "SET Flag = 1
				WHERE Notification.NotificationID = :notifID";

		$parameters = array(
				":notifID" => $notifID
		);

		$GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}
    
    
    


}