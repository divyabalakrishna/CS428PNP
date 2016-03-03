<?php

class EventModel extends Model
{

	public function insertEvent($hostID, $name, $description, $date, $time, $address, $capacity, $tagID) {
		$sql = "INSERT INTO Event (HostID, Name, Description, Time, Address, Capacity, TagID)
				VALUES (:hostID, :name, :description, STR_TO_DATE(:time, '%m/%d/%Y %h:%i %p'), :address, :capacity, :tagID)";

		$parameters = array(
				":hostID" => $hostID,
				":name" => $name,
				":description" => $description,
				":time" => $date . " " . $time,
				":address" => $address,
				":capacity" => $capacity,
				":tagID" => $tagID
		);

		return $GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}

	public function updateEvent($eventID, $hostID, $name, $description, $date, $time, $address, $capacity, $tagID) {
		$sql = "UPDATE Event
				SET Name = :name,
					Description = :description,
					Time = STR_TO_DATE(:time, '%m/%d/%Y %h:%i %p'),
					Address = :address,
					Capacity = :capacity,
					TagID = :tagID
				WHERE Event.EventID = :eventID
					AND Event.HostID = :hostID";

		$parameters = array(
				":eventID" => $eventID,
				":hostID" => $hostID,
				":name" => $name,
				":description" => $description,
				":time" => $date . " " . $time,
				":address" => $address,
				":capacity" => $capacity,
				":tagID" => $tagID
		);

		$GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}

	public function deleteEvent($eventID, $hostID) {
		$sql = "DELETE
				FROM Event
				WHERE Event.EventID = :eventID
					AND Event.HostID = :hostID";

		$parameters = array(
				":eventID" => $eventID,
				":hostID" => $hostID
		);

		$GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}

	public function getEvent($eventID, $hostID = "")
	{
		$sql = "SELECT Event.*,
					Tag.Name AS TagName
				FROM Event
				LEFT JOIN Tag ON Tag.TagID = Event.TagID
				WHERE Event.EventID = :eventID";

		if (is_numeric($hostID)) {
			$sql .= " AND Event.HostID = :hostID";
		}

		$parameters = array(':eventID' => $eventID);
		if (is_numeric($hostID)) {
			$parameters[":hostID"] = $hostID;
		}

		return $GLOBALS["beans"]->queryHelper->getSingleRowObject($this->db, $sql, $parameters);
	}

	public function getHostedEvents($hostID, $timeType = "")
	{
		$sql = "SELECT Event.*,
					DATE_FORMAT(Event.Time, '%m/%d/%Y %h:%i %p') AS Formatted_Time,
					Tag.Name AS TagName,
					Tag.Icon AS TagIcon,
					IFNULL(Participant_Summary.Participant_Count, 0) AS Participant_Count
				FROM Event
				LEFT JOIN Tag ON Tag.TagID = Event.TagID
				LEFT JOIN (
					SELECT EventID, COUNT(UserID) AS Participant_Count
					FROM Participant
					GROUP BY EventID
				) Participant_Summary ON Participant_Summary.EventID = Event.EventID
				WHERE Event.HostID = :hostID";

		if (strcasecmp($timeType, "future") == 0)
		{
			$sql .= " AND Event.Time > NOW()";
		}
		else if (strcasecmp($timeType, "past") == 0)
		{
			$sql .= " AND Event.Time <= NOW()";
		}

		$sql .= " ORDER BY Event.Time";

		$parameters = array(":hostID" => $hostID);

		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql, $parameters);
	}

	public function getJoinedEvents($userID, $timeType = "")
	{
		$sql = "SELECT Event.*,
					DATE_FORMAT(Event.Time, '%m/%d/%Y %h:%i %p') AS Formatted_Time,
					Tag.Name AS TagName,
					Tag.Icon AS TagIcon
				FROM Event
				INNER JOIN Participant ON Participant.EventID = Event.EventID
				LEFT JOIN Tag ON Tag.TagID = Event.TagID
				WHERE Participant.UserID = :userID";

		if (strcasecmp($timeType, "future") == 0)
		{
			$sql .= " AND Event.Time > NOW()";
		}
		else if (strcasecmp($timeType, "past") == 0)
		{
			$sql .= " AND Event.Time <= NOW()";
		}

		$sql .= " ORDER BY Event.Time";

		$parameters = array(":userID" => $userID);

		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql, $parameters);
	}

}