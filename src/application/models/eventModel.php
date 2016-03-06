<?php

class EventModel extends Model
{

	public function insertEvent($hostID, $name, $description, $date, $time, $address, $capacity, $tagID, $lat, $lon) {
		$sql = "INSERT INTO Event (HostID, Name, Description, Time, Address, Capacity, TagID, Lat, Lon)
				VALUES (:hostID, :name, :description, STR_TO_DATE(:time, '%m/%d/%Y %h:%i %p'), :address, :capacity, :tagID, :lat, :lon)";

		$parameters = array(
				":hostID" => $hostID,
				":name" => $name,
				":description" => $description,
				":time" => $date . " " . $time,
				":address" => $address,
				":capacity" => $capacity,
				":tagID" => $tagID,
                ":lat" => $lat,
                ":lon" => $lon
		);

		return $GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}

	public function updateEvent($eventID, $hostID, $name, $description, $date, $time, $address, $capacity, $tagID, $lat, $lon) {
		$sql = "UPDATE Event
				SET Name = :name,
					Description = :description,
					Time = STR_TO_DATE(:time, '%m/%d/%Y %h:%i %p'),
					Address = :address,
					Capacity = :capacity,
					TagID = :tagID,
                    Lat = :lat,
                    Lon = :lon
                    
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
				":tagID" => $tagID,
				":lat" => $lat,
				":lon" => $lon
            
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
					DATE_FORMAT(Event.Time, '%m/%d/%Y') AS FormattedDate,
					DATE_FORMAT(Event.Time, '%h:%i %p') AS FormattedTime,
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

	public function getHostedEvents($hostID, $timeType = "", $limit="")
	{
		$sql = "SELECT Event.*,
					DATE_FORMAT(Event.Time, '%m/%d/%Y') AS FormattedDate,
					DATE_FORMAT(Event.Time, '%h:%i %p') AS FormattedTime,
					DATE_FORMAT(Event.Time, '%m/%d/%Y %h:%i %p') AS FormattedDateTime,
					Tag.Name AS TagName,
					Tag.Icon AS TagIcon,
					IFNULL(ParticipantSummary.ParticipantCount, 0) AS ParticipantCount
				FROM Event
				LEFT JOIN Tag ON Tag.TagID = Event.TagID
				LEFT JOIN (
					SELECT EventID, COUNT(UserID) AS ParticipantCount
					FROM Participant
					GROUP BY EventID
				) ParticipantSummary ON ParticipantSummary.EventID = Event.EventID
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

		if (is_numeric($limit)) {
			$sql .= " LIMIT " . $limit;
		}

		$parameters = array(":hostID" => $hostID);

		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql, $parameters);
	}

	public function getJoinedEvents($userID, $timeType = "", $limit="")
	{
		$sql = "SELECT Event.*,
					DATE_FORMAT(Event.Time, '%m/%d/%Y') AS FormattedDate,
					DATE_FORMAT(Event.Time, '%h:%i %p') AS FormattedTime,
					DATE_FORMAT(Event.Time, '%m/%d/%Y %h:%i %p') AS FormattedDateTime,
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

		if (is_numeric($limit)) {
			$sql .= " LIMIT " . $limit;
		}

		$parameters = array(":userID" => $userID);

		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql, $parameters);
	}

	public function updateEventImage($eventID, $hostID, $image) {
		$sql = "UPDATE Event
				SET Image = :image
				WHERE Event.EventID = :eventID
					AND Event.HostID = :hostID";

		$parameters = array(
				":eventID" => $eventID,
				":hostID" => $hostID,
				":image" => $image
		);

		$GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}

	public function getSearchEvents($userID, $Lat, $Lon)
	{
//        $Address = urlencode("911 W.Springfield Ave, Urbana, IL");
//        $request_url = "http://maps.googleapis.com/maps/api/geocode/xml?address=".$Address."&sensor=true";
//        $xml = simplexml_load_file($request_url) or die("url not loading");
//        $status = $xml->status;
//        if ($status=="OK") {
//          $Lat = $xml->result->geometry->location->lat;
//          $Lon = $xml->result->geometry->location->lng;
//        }
        
        $sql = "SELECT Event.*,DATE_FORMAT(Event.Time, '%m/%d/%Y %h:%i %p') AS FormattedDateTime, 
    					Tag.Name AS TagName,
                        Tag.Icon AS TagIcon,
                ( 3959 * acos( cos( radians(".$Lat.") ) * cos( radians( lat ) ) 
                * cos( radians( Lon ) - radians(".$Lon.") ) + sin( radians(".$Lat.") ) * sin(radians(lat)) ) ) AS distance 
                FROM event 
				LEFT JOIN Tag ON Tag.TagID = Event.TagID                
                HAVING distance < 2
                ORDER BY distance";
        

		$parameters = array(":userID" => $userID);
        echo $Lat.','.$Lon;
		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql, $parameters);
	}

	public function deleteParticipant($eventID, $userID = "") {
		$sql = "DELETE
				FROM Participant
				WHERE Participant.EventID = :eventID";

		if (is_numeric($userID)) {
			$sql .= " AND Participant.UserID = :userID";
		}

		$parameters = array(":eventID" => $eventID);
		if (is_numeric($userID)) {
			$parameters[":userID"] = $userID;
		}

		$GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}

}
