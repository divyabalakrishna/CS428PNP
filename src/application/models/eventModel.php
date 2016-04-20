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

	public function copyEvent($eventID, $date, $time) {
		$sql = "INSERT INTO Event (HostID, Name, Description, Time, Address, Capacity, TagID, Lat, Lon)
				SELECT HostID, Name, Description, STR_TO_DATE(:time, '%m/%d/%Y %h:%i %p'), Address, Capacity, TagID, Lat, Lon
				FROM Event
				WHERE EventID = :eventID";

		$parameters = array(
				":eventID" => $eventID,
				":time" => $date . " " . $time
		);

		return $GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);;
	}

	public function copyParticipant($eventID, $newEventID) {
		$sql = "INSERT INTO Participant (EventID, UserID)
				SELECT :newEventID, UserID
				FROM Participant
				WHERE EventID = :eventID
					AND NOT EXISTS (
						SELECT New_Participant.UserID
						FROM Participant New_Participant
						WHERE New_Participant.EventID = :newEventID
							AND New_Participant.UserID = Participant.UserID)";

		$parameters = array(
				":eventID" => $eventID,
				":newEventID" => $newEventID
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
					Tag.Name AS TagName
				FROM Event
				INNER JOIN Participant ON Participant.EventID = Event.EventID
				LEFT JOIN Tag ON Tag.TagID = Event.TagID
				WHERE Participant.UserID = :userID
					AND Event.HostID <> Participant.UserID";

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
	
	public function getPastEvents()
	{
		$sql = "SELECT EVENT.*,
					DATE_FORMAT(Event.Time, '%m/%d/%Y') AS FormattedDate,
					DATE_FORMAT(Event.Time, '%h:%i %p') AS FormattedTime,
					DATE_FORMAT(Event.Time, '%m/%d/%Y %h:%i %p') AS FormattedDateTime,
					Tag.Name AS TagName
				FROM Event
				LEFT JOIN Tag ON Tag.TagID = Event.TagID
				WHERE Event.Time <= NOW()";
	
		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql);
	}
	
	public function getFeed($userID)
	{
		$sql = "SELECT EVENT.*,
					DATE_FORMAT(Event.Time, '%m/%d/%Y') AS FormattedDate,
					DATE_FORMAT(Event.Time, '%h:%i %p') AS FormattedTime,
					DATE_FORMAT(Event.Time, '%m/%d/%Y %h:%i %p') AS FormattedDateTime,
					Tag.Name AS TagName
				FROM Event
				LEFT JOIN Tag ON Tag.TagID = Event.TagID
				WHERE NOT EXISTS (
                    	SELECT Participant.UserID 
                    	FROM Participant 
                    	WHERE Participant.EventID = Event.EventID 
                    		AND Participant.UserID = :userID) 
					AND Event.HostID <> :userID AND Event.Time > NOW()";
		
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

	public function insertMedia($eventID, $userID, $image) {
		$sql = "INSERT INTO Media (EventID, UserID, Image)
				VALUES (:eventID, :userID, :image)";

		$parameters = array(
				":eventID" => $eventID,
				":userID" => $userID,
				":image" => $image,
		);

		return $GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}

	public function getSearchEvents($userID, $radius, $Lat, $Lon)
	{

        $sql = "SELECT Event.*,DATE_FORMAT(Event.Time, '%m/%d/%Y %h:%i %p') AS FormattedDateTime, 
    					Tag.Name AS TagName,
                ( 3959 * acos( cos( radians(".$Lat.") ) * cos( radians( lat ) ) 
                * cos( radians( Lon ) - radians(".$Lon.") ) + sin( radians(".$Lat.") ) * sin(radians(lat)) ) ) AS distance 
                FROM Event 
				LEFT JOIN Tag ON Tag.TagID = Event.TagID
				WHERE Event.Time > NOW()
                HAVING distance < ".$radius."
                ORDER BY distance";
        
		$parameters = array(":userID" => $userID);
		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql, $parameters);
	}

	public function deleteParticipants($eventID, $userID = "") {
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

	public function insertParticipant($eventID, $userID) {
		$sql = "INSERT INTO Participant (EventID, UserID)
				VALUES (:eventID, :userID)";

		$parameters = array(
				":eventID" => $eventID,
				":userID" => $userID
		);

		return $GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}

	public function getComments($eventID, $commentID = "") {
		$sql = "SELECT Comment.*,
					User.FirstName,
					User.LastName,
					User.Picture
				FROM Comment
				INNER JOIN User ON User.UserID = Comment.UserID
				WHERE Comment.EventID = :eventID";

		if (is_numeric($commentID)) {
			$sql .= " AND Comment.CommentID = :commentID";
		}

		$sql .= " ORDER BY Comment.ParentID, Comment.CommentID";

		$parameters = array(
				":eventID" => $eventID
		);
		if (is_numeric($commentID)) {
			$parameters[":commentID"] = $commentID;
		}

		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql, $parameters);
	}

	public function insertComment($eventID, $userID, $parentID, $text) {
		$sql = "INSERT INTO Comment (EventID, UserID, ParentID, Text)
				VALUES (:eventID, :userID, :parentID, :text)";

		$parameters = array(
				":eventID" => $eventID,
				":userID" => $userID, 
				":parentID" => $parentID,
				":text" => $text
		);

		$commentID = $GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);

		if (!is_numeric($parentID)) {
			$sql = "UPDATE Comment
					SET ParentID = CommentID
					WHERE Comment.CommentID = :commentID";

			$parameters = array(
					":commentID" => $commentID
			);

			$GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
		}

		return $commentID;
	}

	public function getParticipants($eventID, $userID = "")
	{
		$sql = "SELECT Participant.*,
					User.FirstName,
					User.LastName,
					User.Picture
				FROM Participant
				INNER JOIN User ON User.UserID = Participant.UserID
				WHERE Participant.EventID = :eventID";

		if (is_numeric($userID)) {
			$sql .= " AND Participant.UserID = :userID";
		}

		$sql .= " ORDER BY User.LastName, User.FirstName";

		$parameters = array(':eventID' => $eventID);
		if (is_numeric($userID)) {
			$parameters[":userID"] = $userID;
		}

		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql, $parameters);
	}
	
	public function getMedia($eventID, $mediaID = "") {
		$sql = "SELECT *
				FROM Media
				WHERE Media.EventID = :eventID";

		if (is_numeric($mediaID)) {
			$sql .= " AND Media.MediaID = :mediaID";
		}

		$sql .= " ORDER BY Media.MediaID";

		$parameters = array(':eventID' => $eventID);
		if (is_numeric($mediaID)) {
			$parameters[":mediaID"] = $mediaID;
		}

		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql, $parameters);
	}

	public function deleteComments($eventID, $commentID = "") {
		$sql = "DELETE
				FROM Comment
				WHERE Comment.EventID = :eventID";

		if (is_numeric($commentID)) {
			$sql .= " AND (Comment.ParentID = :commentID
					OR Comment.CommentID = :commentID)";
		}

		$parameters = array(":eventID" => $eventID);
		if (is_numeric($commentID)) {
			$parameters[":commentID"] = $commentID;
		}

		$GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}

	public function deleteMedia($eventID, $mediaID = "") {
		$sql = "DELETE
				FROM Media
				WHERE Media.EventID = :eventID";

		if (is_numeric($mediaID)) {
			$sql .= " AND Media.MediaID = :mediaID";
		}

		$parameters = array(":eventID" => $eventID);
		if (is_numeric($mediaID)) {
			$parameters[":mediaID"] = $mediaID;
		}

		$GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}
    
	public function getTagName($tagID) {

		$sql = "SELECT Name
				FROM Tag
				WHERE TagID = :tagID";  

		$parameters = array(":tagID" => $tagID);

		return $GLOBALS["beans"]->queryHelper->getSingleRowObject($this->db, $sql, $parameters);
    
    }
}
