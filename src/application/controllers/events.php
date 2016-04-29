<?php

class Events {
	/**
	 * Get a list of all the events hosted by the user  
	 * @param string $_POST["timeType"]     
	 */ 
	public function listHosted() {
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");

		$timeType = "future";
		//make sure the key exist
		if (array_key_exists("timeType", $_POST)) {
			$timeType = $_POST["timeType"];
		}

		$events = $GLOBALS["beans"]->eventModel->getHostedEvents($userID, $timeType);

		require APP . 'views/_templates/header.php';
		require APP . 'views/events/index_hosted.php';
		require APP . 'views/_templates/footer.php';
	}

	/**
	 * Get a list of all the events joined by the user  
	 * @param string $_POST["timeType"]     
	 */ 
	public function listJoined() {
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");

		$timeType = "future";
		//make sure the key exist
		if (array_key_exists("timeType", $_POST)) {
			$timeType = $_POST["timeType"];
		}

		$events = $GLOBALS["beans"]->eventModel->getJoinedEvents($userID, $timeType);

		require APP . 'views/_templates/header.php';
		require APP . 'views/events/index_joined.php';
		require APP . 'views/_templates/footer.php';
	}

	/**
	 * Get a list of all the events in the future  
	 * @param string $_POST["gmap-lat2"] 
	 * @param string $_POST["gmap-lon2"] 	
	 * @param string $_POST["tag"] 	 
	 * @param string $_POST["old"] 	
	 */ 
	public function listSearch() {
		$latitude = $GLOBALS["beans"]->siteHelper->getDefaultLat();
		$longitude = $GLOBALS["beans"]->siteHelper->getDefaultLon();

		if (isset($_COOKIE['latitude']) && is_numeric($_COOKIE['latitude'])) {
			$latitude = $_COOKIE["latitude"];
		}
		if (isset($_COOKIE['longitude']) && is_numeric($_COOKIE['longitude'])) {
			$longitude = $_COOKIE["longitude"];
		}

		if (isset($_POST["gmap-lat2"]) && is_numeric($_POST["gmap-lat2"])) {
			$latitude = $_POST["gmap-lat2"];
		}

		if (isset($_POST["gmap-lon2"]) && is_numeric($_POST["gmap-lon2"])) {
			$longitude = $_POST["gmap-lon2"];
		}
		$tag = false;
		$old = false;

		if (isset($_POST["tag"])) {
			$tag = true;
		}
		if (isset($_POST["old"])) {
			$old = true;
		}

		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$user = $GLOBALS["beans"]->userModel->getProfile($userID);
		
		if (!$user->Radius) {
			$user->Radius = 2;
		}
		$events = $GLOBALS["beans"]->eventModel->getSearchEvents($userID, $user->Radius, $latitude, $longitude, $tag, $old);

		require APP . 'views/_templates/header.php';
		require APP . 'views/events/index_search.php';
		require APP . 'views/_templates/footer.php';
	}

	public function genXML() {
		$latitude = $GLOBALS["beans"]->siteHelper->getDefaultLat();
		$longitude = $GLOBALS["beans"]->siteHelper->getDefaultLon();

		if (isset($_COOKIE['latitude'])) {
			$latitude = $_COOKIE["latitude"];
		}
		if (isset($_COOKIE['longitude'])) {
			$longitude = $_COOKIE["longitude"];
		}

		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$user = $GLOBALS["beans"]->userModel->getProfile($userID);

		if (!$user->Radius) {
			$user->Radius = 2;
		}
		$events = $GLOBALS["beans"]->eventModel->getSearchEvents($userID, $user->Radius, $latitude, $longitude);

		require APP . 'views/events/xml.php';
	}

	/**
	 * Get all the details for an event    
	 * @param string $eventID
	 */ 
	public function view($eventID) {
		//make sure the eventID is valid
		if (!is_numeric($eventID)) {
			header('location: ' . URL_WITH_INDEX_FILE);
		}
		
		//get all the parameters
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$event = $GLOBALS["beans"]->eventModel->getEvent($eventID);
		$participants = $GLOBALS["beans"]->eventModel->getParticipants($eventID);
		$userParticipation = $GLOBALS["beans"]->eventModel->getParticipants($eventID, $userID);
		$comments = $GLOBALS["beans"]->eventModel->getComments($eventID);
		$media = $GLOBALS["beans"]->eventModel->getMedia($eventID);
		
		require APP . 'views/_templates/header.php';
		require APP . 'views/events/view.php';
		require APP . 'views/_templates/footer.php';
	}

	/**
	 * Redirect to the recreate event page   
	 * @param string $eventID
	 */ 	
	public function recreate($eventID) {
		require APP . 'views/_templates/header.php';
		require APP . 'views/events/recreate.php';
		require APP . 'views/_templates/footer.php';
	}

	/**
	 * Recreate an existing past event  
	 * @param string $_POST["eventID"]    
	 * @param string $_POST["date"] 
	 * @param string $_POST["time"] 	 
	 */
	public function recreateSave() {
		//get all the parameters
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$event = $GLOBALS["beans"]->eventModel->getEvent($_POST["eventID"]);

		$newEventID = $GLOBALS["beans"]->eventModel->copyEvent(
				$_POST["eventID"],
				$_POST["date"],
				$_POST["time"]
		);

		$GLOBALS["beans"]->eventModel->copyParticipant(
				$_POST["eventID"],
				$newEventID
		);

		//make sure the image exist
		if ($event->Image != "") {
			$fileName = $GLOBALS["beans"]->fileHelper->copyUploadedFile("event", $event->Image, $newEventID);
			$GLOBALS["beans"]->eventModel->updateEventImage($newEventID, $userID, $fileName);
		}

		header('location: ' . URL_WITH_INDEX_FILE . 'events/view/' . $newEventID);
	}

	/**
	 * Redirect to the edit page of an event  	 
	 */	
	public function edit($eventID = "") {
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$event = $GLOBALS["beans"]->eventModel->getEvent($eventID, $userID);
		$tags = $GLOBALS["beans"]->resourceModel->getTags();

		require APP . 'views/_templates/header.php';
		require APP . 'views/events/edit.php';
		require APP . 'views/_templates/footer.php';
	}

	/**
	 * Save the edited event detail  
	 * @param string $_POST["eventID"]    
	 * @param string $_POST["name"]
	 * @param string $_POST["description"]
	 * @param string $_POST["date"]
	 * @param string $_POST["time"]
	 * @param string $_POST["address"]
	 * @param string $_POST["capacity"]
	 * @param string $_POST["tagID"]
	 * @param string $_POST["gmap-lat"]
	 * @param string $_POST["gmap-lon"]	 
	 */
	public function save() {
		//get all the parameters
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$eventID = $_POST["eventID"];
		$performUpload = false;
		$oldImage = "";
		$backToEdit = false;

		//make sure the eventID is valid
		if (is_numeric($eventID)) {
			$event = $GLOBALS["beans"]->eventModel->getEvent($eventID);
			
			//If the event already exist, modify it 
			if ($userID == $event->HostID) {
				$GLOBALS["beans"]->eventModel->updateEvent(
						$eventID,
						$userID,
						$_POST["name"],
						$_POST["description"],
						$_POST["date"],
						$_POST["time"],
						$_POST["address"],
						$_POST["capacity"],
						$_POST["tagID"],
						$_POST["gmap-lat"],
						$_POST["gmap-lon"]
				);

				$oldImage = $event->Image;
				$performUpload = true;
			}
		}//if the event is not exist, create it
		else {
			$eventID = $GLOBALS["beans"]->eventModel->insertEvent(
					$userID,
					$_POST["name"],
					$_POST["description"],
					$_POST["date"],
					$_POST["time"],
					$_POST["address"],
					$_POST["capacity"],
					$_POST["tagID"],
					$_POST["gmap-lat"],
					$_POST["gmap-lon"]
			);

			// Insert host as participant
			$GLOBALS["beans"]->eventModel->insertParticipant(
					$eventID,
					$userID
			);

			$performUpload = true;
		}

		//if there is a meida needs to be uploaded
		if ($performUpload) {
			$result = $GLOBALS["beans"]->fileHelper->uploadFile("image", "event", "jpg,jpeg,png,bmp", "event image", 2097152, $eventID);

			if ($result->fileUploaded) {
				$GLOBALS["beans"]->eventModel->updateEventImage($eventID, $userID, $result->fileName);

				if ($oldImage != "" && $oldImage != $result->fileName) {
					$GLOBALS["beans"]->fileHelper->deleteUploadedFile("event", $oldImage);
				}
			}
			else if ($result->errorMessage != "") {
				$GLOBALS["beans"]->siteHelper->addAlert("danger", $result->errorMessage);
				$backToEdit = true;
			}
		}
		
		//redirect to different page
		if ($backToEdit) {
			header('location: ' . URL_WITH_INDEX_FILE . 'events/edit/' . $eventID);
		}
		else {
			header('location: ' . URL_WITH_INDEX_FILE . 'events/view/' . $eventID);
		}
	}
	
	/**
	 * Get the media needs to be uploaded  
	 * @param string $_POST["eventID"]    
	 * @param string $_POST['image'] 
	 * @param string $_POST['name'] 	 
	 */	
	public function upload() {
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$eventID = $_POST["eventID"];
		$success = false;

		$total = count($_FILES['image']['name']);

		// Loop through each file
		for ($i = 0; $i < $total; $i++) {
			$newName = $eventID."-".$userID."-".$GLOBALS["beans"]->stringHelper->genString(); 
			$result = $GLOBALS["beans"]->fileHelper->uploadFile("image", "media", "jpg,jpeg,png,bmp,mp4", "media image", 8097152, $newName,$i);

			if ($result->fileUploaded) {
				$GLOBALS["beans"]->eventModel->insertMedia($eventID, $userID, $result->fileName);
				$success = true;
			}
			else if ($result->errorMessage != "") {
				$GLOBALS["beans"]->siteHelper->addAlert("danger", $result->errorMessage);
			}
		}

		// Add notification to all participants
		if ($success) {
			$this->addUploadNotif($eventID);
		}
		
		header('location: ' . URL_WITH_INDEX_FILE . 'events/view/' . $eventID);
	}

	/**
	 * Delete an event  	
	 * @param string $eventID
	 */
	public function delete($eventID) {
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$event = $GLOBALS["beans"]->eventModel->getEvent($eventID);

		//only the hoster can delete the event
		if ($userID == $event->HostID) {
			$GLOBALS["beans"]->eventModel->deleteMedia($eventID);
			$GLOBALS["beans"]->eventModel->deleteComments($eventID);
			$GLOBALS["beans"]->eventModel->deleteParticipants($eventID);
			$GLOBALS["beans"]->eventModel->deleteEvent($eventID, $userID);
		}

		header('location: ' . URL_WITH_INDEX_FILE . 'events/listHosted');
	}

	/**
	 * Comment on an event  
	 * @param string $_POST["eventID"]    
	 * @param string $_POST["parentID"] 
	 * @param string $_POST["text"] 	 
	 */	
	public function reply() {
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$eventID = $_POST["eventID"];

		$GLOBALS["beans"]->eventModel->insertComment(
				$eventID,
				$userID,
				$_POST["parentID"],
				$_POST["text"]
		);

 		header('location: ' . URL_WITH_INDEX_FILE . 'events/view/' . $eventID);
	}

	/**
	 * Join an event 
	 * @param string $eventID
	 */
	public function join($eventID) {
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$participant = $GLOBALS["beans"]->eventModel->getParticipants($eventID, $userID);

		//only insert the participant if not in the database already
		if (count($participant) == 0) {
			$GLOBALS["beans"]->eventModel->insertParticipant($eventID, $userID);
		}

		header('location: ' . URL_WITH_INDEX_FILE . 'events/view/' . $eventID);
	}

	/**
	 * Leave an event  
	 * @param string $eventID
	 */	
	public function leave($eventID) {
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$event = $GLOBALS["beans"]->eventModel->getEvent($eventID);
		$participant = $GLOBALS["beans"]->eventModel->getParticipants($eventID, $userID);

		//only delete the participant if in the database already and not the hoster
		if (count($participant) > 0 && $userID != $event->HostID) {
			$GLOBALS["beans"]->eventModel->deleteParticipants($eventID, $userID);
		}

		header('location: ' . URL_WITH_INDEX_FILE . 'events/view/' . $eventID);
	}

	/**
	 * Delete a comment from an event  
	 * @param string $eventID
	 * @param string $commentID
	 */	
	public function deleteComment($eventID, $commentID) {
		// We do not want to accidentally delete all comments in case commentID is blank, so change to a dummy number
		if (!is_numeric($commentID)) {
			$commentID = -1;
		}

		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$comment = $GLOBALS["beans"]->eventModel->getComments($eventID, $commentID);

		//delete all the comment
		if (count($comment) > 0 && $userID == $comment[0]->UserID) {
			$GLOBALS["beans"]->eventModel->deleteComments($eventID, $commentID);
		}

		header('location: ' . URL_WITH_INDEX_FILE . 'events/view/' . $eventID);
	}

	/**
	 * Delete a media from an event  
	 * @param string $eventID
	 * @param string $mediaID
	 */	
	public function deleteMedia($eventID, $mediaID) {
		// We do not want to accidentally delete all Medias in case mediaID is blank, so change to a dummy number
		if (!is_numeric($mediaID)) {
			$mediaID = -1;
		}

		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$media = $GLOBALS["beans"]->eventModel->getMedia($eventID,$mediaID);

		if ($userID == $media[0]->UserID) {
			$GLOBALS["beans"]->eventModel->deleteMedia($eventID, $mediaID);
			$GLOBALS["beans"]->fileHelper->deleteUploadedFile("media", $media[0]->Image);
		}

		header('location: ' . URL_WITH_INDEX_FILE . 'events/view/' . $eventID);
	}

	/**
	 * Add a notification to the user  
	 * @param string $eventID
	 */	
	public function addUploadNotif($eventID) {
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$event = $GLOBALS["beans"]->eventModel->getEvent($eventID);
		$participants = $GLOBALS["beans"]->eventModel->getParticipants($eventID);

		foreach ($participants as $user) { 

			if ($user->UserID != $userID) {

				if ($event->Image) {
					$imgLink = "/uploads/event/" . $event->Image;
				}
				else {
					$imgLink = "/public/img/sports/" . $event->TagName . ".png";
				}

				//Insert Notifications
				$GLOBALS["beans"]->notifModel->insertNotif(
						$user->UserID,
						$event->EventID,
						"Your friend was uploading a new media on ". $event->Name . ".",
						"/events/view/" . $event->EventID,
						$imgLink
				);
			}
		}
	}

}