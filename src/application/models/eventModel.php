<?php

class EventModel extends Model {
	/**
	 * Insert the event details to the database  
	 * @param string $hostID    
	 * @param string $name
	 * @param string $description
	 * @param string $date
	 * @param string $time
	 * @param string $address
	 * @param string $capacity
	 * @param string $tagID
	 * @param string $latitude
	 * @param string $longitude
	 */
	public function insertEvent($hostID, $name, $description, $date, $time, $address, $capacity, $tagID, $latitude, $longitude) {
		$sql = "INSERT INTO Event (HostID, Name, Description, Time, Address, Capacity, TagID, Lat, Lon)
				VALUES (:hostID, :name, :description, STR_TO_DATE(:time, '%m/%d/%Y %h:%i %p'), :address, :capacity, :tagID, :latitude, :longitude)";

		$parameters = array(
				":hostID" => $hostID,
				":name" => $name,
				":description" => $description,
				":time" => $date . " " . $time,
				":address" => $address,
				":capacity" => $capacity,
				":tagID" => $tagID,
				":latitude" => $latitude,
				":longitude" => $longitude
		);

		return $GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}

	/**
	 * Update the event details to the database  
	 * @param string $eventID 
	 * @param string $hostID    
	 * @param string $name
	 * @param string $description
	 * @param string $date
	 * @param string $time
	 * @param string $address
	 * @param string $capacity
	 * @param string $tagID
	 * @param string $latitude
	 * @param string $longitude
	 */
	public function updateEvent($eventID, $hostID, $name, $description, $date, $time, $address, $capacity, $tagID, $latitude, $longitude) {
		$sql = "UPDATE Event
				SET Name = :name,
					Description = :description,
					Time = STR_TO_DATE(:time, '%m/%d/%Y %h:%i %p'),
					Address = :address,
					Capacity = :capacity,
					TagID = :tagID,
					Lat = :latitude,
					Lon = :longitude
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
				":latitude" => $latitude,
				":longitude" => $longitude
		);

		$GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}

	/**
	 * Delete the event details from the database  
	 * @param string $eventID    
	 * @param string $hostID
	 */
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

	/**
	 * Copy the event details in the database  
	 * @param string $eventID    
	 * @param string $date
	 * @param string $time
	 */
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

	/**
	 * Copy the Participant in the database  
	 * @param string $eventID    
	 * @param string $newEventID
	 */
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

	/**
	 * Get the event details from the database  
	 * @param string $eventID    
	 * @param string $hostID
	 */
	public function getEvent($eventID, $hostID = "") {
		$sql = "SELECT Event.*,
					DATE_FORMAT(Event.Time, '%m/%d/%Y') AS FormattedDate,
					DATE_FORMAT(Event.Time, '%h:%i %p') AS FormattedTime,
					Tag.Name AS TagName,
					User.FirstName AS HostFirstName,
					User.LastName AS HostLastName
				FROM Event
				LEFT JOIN Tag ON Tag.TagID = Event.TagID
				LEFT JOIN User ON Event.HostID = User.UserID
				WHERE Event.EventID = :eventID";
				
		//make sure hostID is valid
		if (is_numeric($hostID)) {
			$sql .= " AND Event.HostID = :hostID";
		}

		$parameters = array(':eventID' => $eventID);
		//make sure hostID is valid
		if (is_numeric($hostID)) {
			$parameters[":hostID"] = $hostID;
		}

		return $GLOBALS["beans"]->queryHelper->getSingleRowObject($this->db, $sql, $parameters);
	}

	/**
	 * Get hosted events details from the database  
	 * @param string $hostID    
	 * @param string $timeType
	 * @param string $limit
	 */
	public function getHostedEvents($hostID, $timeType = "", $limit = "") {
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

		//change timetype
		if (strcasecmp($timeType, "future") == 0) {
			$sql .= " AND Event.Time > NOW()";
		}
		else if (strcasecmp($timeType, "past") == 0) {
			$sql .= " AND Event.Time <= NOW()";
		}

		$sql .= " ORDER BY Event.Time";

		//make sure limit is valid
		if (is_numeric($limit)) {
			$sql .= " LIMIT " . $limit;
		}

		$parameters = array(":hostID" => $hostID);

		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql, $parameters);
	}

	/**
	 * Get joined events details from the database  
	 * @param string $hostID    
	 * @param string $timeType
	 * @param string $limit
	 */
	public function getJoinedEvents($userID, $timeType = "", $limit = "") {
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

		//change timeType
		if (strcasecmp($timeType, "future") == 0) {
			$sql .= " AND Event.Time > NOW()";
		}
		else if (strcasecmp($timeType, "past") == 0) {
			$sql .= " AND Event.Time <= NOW()";
		}

		$sql .= " ORDER BY Event.Time";

		//make sure limit is valid
		if (is_numeric($limit)) {
			$sql .= " LIMIT " . $limit;
		}

		$parameters = array(":userID" => $userID);

		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql, $parameters);
	}

	/**
	 * Get past events details from the database  
	 */	
	public function getPastEvents() {
		$sql = "SELECT Event.*,
					DATE_FORMAT(Event.Time, '%m/%d/%Y') AS FormattedDate,
					DATE_FORMAT(Event.Time, '%h:%i %p') AS FormattedTime,
					DATE_FORMAT(Event.Time, '%m/%d/%Y %h:%i %p') AS FormattedDateTime,
					Tag.Name AS TagName
				FROM Event
				LEFT JOIN Tag ON Tag.TagID = Event.TagID
				WHERE Event.Time <= NOW()";
	
		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql);
	}

	/**
	 * Get future events details from the database  
	 * @param string $userID    
	 */	
	public function getFeed($userID) {
		$sql = "SELECT Event.*,
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
							AND Participant.UserID = :userID
					) 
					AND Event.HostID <> :userID
					AND Event.Time > NOW()";
		
		$parameters = array(":userID" => $userID);
	
		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql, $parameters);
	}

	/**
	 * Update the event image to the database  
	 * @param string $eventID    
	 * @param string $hostID   
	 * @param string $image   
	 */	
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

	/**
	 * Insert the media to the database  
	 * @param string $eventID    
	 * @param string $userID   
	 * @param string $image   
	 */	
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

	/**
	 * Get search events details from the database  
	 * @param string $userID    
	 * @param string $radius   
	 * @param string $latitude   
	 * @param string $longitude 
	 * @param string $tag 
	 * @param string $old 
	 */	
	public function getSearchEvents($userID, $radius, $latitude, $longitude, $tag, $old) {
		$sql = "SELECT Event.*,
					DATE_FORMAT(Event.Time, '%m/%d/%Y %h:%i %p') AS FormattedDateTime, 
					Tag.Name AS TagName,
					(3959 * ACOS(COS(RADIANS(" . $latitude . ")) * COS(RADIANS(Lat)) * COS(RADIANS(Lon) - RADIANS(" . $longitude . ")) + SIN(RADIANS(" . $latitude . ") ) * SIN(RADIANS(Lat)))) AS Distance
				FROM Event
				LEFT JOIN Tag ON Tag.TagID = Event.TagID
				WHERE Event.Time > NOW()
				HAVING distance < " . $radius . "
				ORDER BY distance";

		//query for old events
		if ($old) {
			$sql = str_replace('WHERE Event.Time > NOW()','',$sql);
		}

		$parameters = array(":userID" => $userID);

		$query = $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql, $parameters);

		//query for tagged events
		if ($tag) {
			function tagToID($tag) {
				return $tag->TagID;
			}
			$tags = $GLOBALS["beans"]->userModel->getUserTags($userID);
			$tagIDs = array_map("tagToID", $tags);
			foreach ($query as $i => $event) {
				if (!in_array($event->TagID, $tagIDs)) {
					unset($query[$i]);
				}
			}
		}

		return $query;
	}

	/**
	 * Delete the participants from the database  
	 * @param string $eventID    
	 * @param string $userID   
	 */
	public function deleteParticipants($eventID, $userID = "") {
		$sql = "DELETE
				FROM Participant
				WHERE Participant.EventID = :eventID";

		//make sure userID is valid
		if (is_numeric($userID)) {
			$sql .= " AND Participant.UserID = :userID";
		}

		$parameters = array(":eventID" => $eventID);
		//make sure userID is valid
		if (is_numeric($userID)) {
			$parameters[":userID"] = $userID;
		}

		$GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}

	/**
	 * Insert the participants to the database  
	 * @param string $eventID    
	 * @param string $userID   
	 */
	public function insertParticipant($eventID, $userID) {
		$sql = "INSERT INTO Participant (EventID, UserID)
				VALUES (:eventID, :userID)";

		$parameters = array(
				":eventID" => $eventID,
				":userID" => $userID
		);

		return $GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}

	/**
	 * Get all comments for an event from the database  
	 * @param string $eventID    
	 * @param string $commentID   
	 */
	public function getComments($eventID, $commentID = "") {
		$sql = "SELECT Comment.*,
					User.FirstName,
					User.LastName,
					User.Picture
				FROM Comment
				INNER JOIN User ON User.UserID = Comment.UserID
				WHERE Comment.EventID = :eventID";

		//make sure commentID is valid
		if (is_numeric($commentID)) {
			$sql .= " AND Comment.CommentID = :commentID";
		}

		$sql .= " ORDER BY Comment.ParentID, Comment.CommentID";

		$parameters = array(
				":eventID" => $eventID
		);
		//make sure commentID is valid
		if (is_numeric($commentID)) {
			$parameters[":commentID"] = $commentID;
		}

		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql, $parameters);
	}

	/**
	 * Insert a comment for an event to the database  
	 * @param string $eventID    
	 * @param string $userID   
	 * @param string $parentID    
	 * @param string $text   
	 */
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

		//make sure parentID is valid
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

	/**
	 * Get all participants for an event from the database  
	 * @param string $eventID    
	 * @param string $userID     
	 */
	public function getParticipants($eventID, $userID = "") {
		$sql = "SELECT Participant.*,
					User.FirstName,
					User.LastName,
					User.Picture
				FROM Participant
				INNER JOIN User ON User.UserID = Participant.UserID
				WHERE Participant.EventID = :eventID";
		
		//make sure userID is valid
		if (is_numeric($userID)) {
			$sql .= " AND Participant.UserID = :userID";
		}

		$sql .= " ORDER BY User.LastName, User.FirstName";

		$parameters = array(':eventID' => $eventID);
		//make sure userID is valid
		if (is_numeric($userID)) {
			$parameters[":userID"] = $userID;
		}

		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql, $parameters);
	}
	
	/**
	 * Get all getMedias for an event from the database  
	 * @param string $eventID    
	 * @param string $mediaID     
	 */	
	public function getMedia($eventID, $mediaID = "") {
		$sql = "SELECT *
				FROM Media
				WHERE Media.EventID = :eventID";

		//make sure mediaID is valid
		if (is_numeric($mediaID)) {
			$sql .= " AND Media.MediaID = :mediaID";
		}

		$sql .= " ORDER BY Media.MediaID";

		$parameters = array(':eventID' => $eventID);
		//make sure mediaID is valid
		if (is_numeric($mediaID)) {
			$parameters[":mediaID"] = $mediaID;
		}

		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql, $parameters);
	}

	/**
	 * Delete comments for an event from the database  
	 * @param string $eventID    
	 * @param string $commentID     
	 */	
	public function deleteComments($eventID, $commentID = "") {
		$sql = "DELETE
				FROM Comment
				WHERE Comment.EventID = :eventID";

		//make sure commentID is valid
		if (is_numeric($commentID)) {
			$sql .= " AND (Comment.ParentID = :commentID
					OR Comment.CommentID = :commentID)";
		}

		$parameters = array(":eventID" => $eventID);
		//make sure commentID is valid
		if (is_numeric($commentID)) {
			$parameters[":commentID"] = $commentID;
		}

		$GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}

	/**
	 * Delete media for an event from the database  
	 * @param string $eventID    
	 * @param string $mediaID     
	 */	
	public function deleteMedia($eventID, $mediaID = "") {
		$sql = "DELETE
				FROM Media
				WHERE Media.EventID = :eventID";

		//make sure mediaID is valid
		if (is_numeric($mediaID)) {
			$sql .= " AND Media.MediaID = :mediaID";
		}

		$parameters = array(":eventID" => $eventID);
		//make sure mediaID is valid
		if (is_numeric($mediaID)) {
			$parameters[":mediaID"] = $mediaID;
		}

		$GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}

	/**
	 * Count the hosted events from the database  
	 * @param string $hostID      
	 */	
	public function countHostedEvents($hostID) {
		$sql = "SELECT COUNT(*) as cnt
				FROM Event
				WHERE Event.HostID = :hostID";

		$parameters = array(":hostID" => $hostID);

		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql, $parameters);
	}

	/**
	 * Count the joined events from the database  
	 * @param string $userID      
	 */	
	public function countJoinedEvents($userID) {
		$sql = "SELECT COUNT(*) as cnt
				FROM Event
				INNER JOIN Participant ON Participant.EventID = Event.EventID
				WHERE Participant.UserID = :userID";

		$parameters = array(":userID" => $userID);

		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql, $parameters);
	}
    
    
}