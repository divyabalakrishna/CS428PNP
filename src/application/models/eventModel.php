<?php

/**
 * This class handles database interaction for event module.
 */
class EventModel extends Model {

	/**
	 * Insert an event record.
	 * @param integer $hostID Host user ID.
	 * @param string $name Name.
	 * @param string $description Description.
	 * @param string $date Date.
	 * @param string $time Time.
	 * @param string $address Address.
	 * @param integer $capacity Capacity.
	 * @param integer $tagID Tag ID.
	 * @param double $latitude Latitude.
	 * @param double $longitude Longitude.
	 * @return integer Event ID.
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
	 * Update an event record.
	 * @param integer $eventID Event ID.
	 * @param integer $hostID Host user ID.
	 * @param string $name Name.
	 * @param string $description Description.
	 * @param string $date Date.
	 * @param string $time Time.
	 * @param string $address Address.
	 * @param integer $capacity Capacity.
	 * @param integer $tagID Tag ID.
	 * @param double $latitude Latitude.
	 * @param double $longitude Longitude.
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
	 * Delete an event record.
	 * @param integer $eventID Event ID.
	 * @param integer $hostID Host user ID.
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
	 * Insert an event record based on an existing event.
	 * @param integer $eventID Original event ID.
	 * @param string $date New event date.
	 * @param string $time New event time.
	 * @return integer New event ID.
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
	 * Insert participant records based on an existing event.
	 * @param integer $eventID Original event ID.
	 * @param integer $newEventID New event ID.
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
	 * Retrieve event details for a given event ID.
	 * @param integer $eventID Event ID.
	 * @param integer $hostID Host user ID.
	 * @return stdClass Query result.
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

		if (is_numeric($hostID)) {
			$sql .= " AND Event.HostID = :hostID";
		}

		$parameters = array(':eventID' => $eventID);
		if (is_numeric($hostID)) {
			$parameters[":hostID"] = $hostID;
		}

		return $GLOBALS["beans"]->queryHelper->getSingleRowObject($this->db, $sql, $parameters);
	}

	/**
	 * Retrieve events that are created by a user.
	 * @param integer $hostID Host user ID.
	 * @param string $timeType Time type filter (future of past). Use empty string to disable this filter.
	 * @param integer $limit Maximum number of results.
	 * @return array Query result.
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

		if (strcasecmp($timeType, "future") == 0) {
			$sql .= " AND Event.Time > NOW()";
		}
		else if (strcasecmp($timeType, "past") == 0) {
			$sql .= " AND Event.Time <= NOW()";
		}

		$sql .= " ORDER BY Event.Time";

		if (is_numeric($limit)) {
			$sql .= " LIMIT " . $limit;
		}

		$parameters = array(":hostID" => $hostID);

		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql, $parameters);
	}

	/**
	 * Retrieve events that are joined by a user.
	 * @param integer $userID User ID.
	 * @param string $timeType Time type filter (future of past). Use empty string to disable this filter.
	 * @param integer $limit Maximum number of results.
	 * @return array Query result.
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

		if (strcasecmp($timeType, "future") == 0) {
			$sql .= " AND Event.Time > NOW()";
		}
		else if (strcasecmp($timeType, "past") == 0) {
			$sql .= " AND Event.Time <= NOW()";
		}

		$sql .= " ORDER BY Event.Time";

		if (is_numeric($limit)) {
			$sql .= " LIMIT " . $limit;
		}

		$parameters = array(":userID" => $userID);

		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql, $parameters);
	}

	/**
	 * Retrieve past events.
	 * @return array Query result.
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
	 * Retrieve future events where the user is neither the event host nor a participant.
	 * @param integer $userID User ID.
	 * @return array Query result.
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
	 * Update event image for a given event ID.
	 * @param integer $eventID Event ID.
	 * @param integer $hostID Host user ID.
	 * @param string $image Event image file name.
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
	 * Insert a media record.
	 * @param integer $eventID Event ID.
	 * @param integer $userID User ID.
	 * @param string $image Media file name.
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
	 * Retrieve events based on a certain location.
	 * @param integer $userID User ID.
	 * @param integer $radius Radius setting.
	 * @param double $latitude Latitude.
	 * @param double $longitude Longitude.
	 * @param boolean $tag True to filter events based on the user interests, false to disable the filter.
	 * @param boolean $old True to retrieve both future and past events, false to retrieve only future events.
	 * @return array Query result.
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

		if ($old) {
			$sql = str_replace('WHERE Event.Time > NOW()','',$sql);
		}

		$parameters = array(":userID" => $userID);

		$query = $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql, $parameters);

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
	 * Delete participant records.
	 * @param integer $eventID Event ID.
	 * @param integer $userID User ID.
	 */
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

	/**
	 * Insert a participant record.
	 * @param integer $eventID Event ID.
	 * @param integer $userID User ID.
	 * @return integer Participant ID.
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
	 * Retrieve event comments.
	 * @param integer $eventID Event ID.
	 * @param integer $commentID Comment ID.
	 * @return array Query result.
	 */
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

	/**
	 * Insert a comment record.
	 * @param integer $eventID Event ID.
	 * @param integer $userID User ID.
	 * @param integer $parentID Parent comment ID.
	 * @param string $text Comment text.
	 * @return integer Comment ID.
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

		// If parent comment ID is blank, store its own ID as the parent comment ID
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
	 * Retrieve event participants.
	 * @param integer $eventID Event ID.
	 * @param integer $userID User ID.
	 * @return array Query result.
	 */
	public function getParticipants($eventID, $userID = "") {
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
	
	/**
	 * Retrieve event media.
	 * @param integer $eventID Event ID.
	 * @param integer $mediaID Media ID.
	 * @return array Query result.
	 */
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

	/**
	 * Delete comment records.
	 * @param integer $eventID Event ID.
	 * @param integer $commentID Comment ID.
	 */
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

	/**
	 * Delete media records.
	 * @param integer $eventID Event ID.
	 * @param integer $mediaID Media ID.
	 */
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

	/**
	 * Count the number of events that are created by a user.
	 * @param integer $hostID Host user ID.
	 * @return array Query result.
	 */
	public function countHostedEvents($hostID) {
		$sql = "SELECT COUNT(*) as cnt
				FROM Event
				WHERE Event.HostID = :hostID";

		$parameters = array(":hostID" => $hostID);

		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql, $parameters);
	}

	/**
	 * Count the number of events that are joined by a user.
	 * @param integer $userID User ID.
	 * @return array Query result.
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