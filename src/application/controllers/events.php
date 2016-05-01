<?php

/**
 * This class acts as a controller for event module.
 */
class Events {

	/**
	 * Display a list of events hosted by the user.
	 */
	public function listHosted() {
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");

		// Past or future filter
		$timeType = "future";
		if (array_key_exists("timeType", $_POST)) {
			$timeType = $_POST["timeType"];
		}

		$events = $GLOBALS["beans"]->eventModel->getHostedEvents($userID, $timeType);

		require APP . 'views/_templates/header.php';
		require APP . 'views/events/index_hosted.php';
		require APP . 'views/_templates/footer.php';
	}

	/**
	 * Display a list of events joined by the user.
	 */
	public function listJoined() {
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");

		// Past or future filter
		$timeType = "future";
		if (array_key_exists("timeType", $_POST)) {
			$timeType = $_POST["timeType"];
		}

		$events = $GLOBALS["beans"]->eventModel->getJoinedEvents($userID, $timeType);

		require APP . 'views/_templates/header.php';
		require APP . 'views/events/index_joined.php';
		require APP . 'views/_templates/footer.php';
	}

	/**
	 * Display a list of events based on a location.
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
			$_SESSION['latitude_s'] = $latitude;
		}

		if (isset($_POST["gmap-lon2"]) && is_numeric($_POST["gmap-lon2"])) {
			$longitude = $_POST["gmap-lon2"];
			$_SESSION['longitude_s'] = $longitude;
		}

		$tag = false;
		$old = false;

		if (isset($_POST["tag"])) {
			$tag = true;
			$_SESSION['tag_s'] = $tag;
		}
		if (isset($_POST["old"])) {
			$old = true;
			$_SESSION['old_s'] = $old;
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

	/**
	 * Generate event marker XML for Google Map.
	 */
	public function genXML() {
		$latitude = $GLOBALS["beans"]->siteHelper->getDefaultLat();
		$longitude = $GLOBALS["beans"]->siteHelper->getDefaultLon();

		if (isset($_COOKIE['latitude'])) {
			$latitude = $_COOKIE["latitude"];
		}
		if (isset($_COOKIE['longitude'])) {
			$longitude = $_COOKIE["longitude"];
		}

		if (isset($_SESSION["latitude_s"]) && is_numeric($_SESSION['latitude_s'])) {
			$latitude = $_SESSION["latitude_s"];
			$_SESSION["latitude_s"] = "";
		}
		if (isset($_SESSION["longitude_s"]) && is_numeric($_SESSION['longitude_s'])) {
			$longitude = $_SESSION["longitude_s"];
			$_SESSION["longitude_s"] = "";
		}

		$tag = false;
		$old = false;

		if (isset($_SESSION["tag_s"])) {
			$tag = $_SESSION["tag_s"];
			$_SESSION["tag_s"] = "";
		}
		if (isset($_SESSION["old_s"])) {
			$old = $_SESSION["old_s"];
			$_SESSION["old_s"] = "";
		}

		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$user = $GLOBALS["beans"]->userModel->getProfile($userID);

		if (!$user->Radius) {
			$user->Radius = 2;
		}
		$events = $GLOBALS["beans"]->eventModel->getSearchEvents($userID, $user->Radius, $latitude, $longitude, $tag, $old);

		require APP . 'views/events/xml.php';
	}

	/**
	 * Display information, participant list, comments, and media for an event.
	 * @param integer $eventID Event ID.
	 */
	public function view($eventID) {
		// Redirect to home page is event ID is invalid
		if (!is_numeric($eventID)) {
			header('location: ' . URL_WITH_INDEX_FILE);
		}

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
	 * Display recreate event form.
	 * @param integer $eventID Event ID.
	 */
	public function recreate($eventID) {
		require APP . 'views/_templates/header.php';
		require APP . 'views/events/recreate.php';
		require APP . 'views/_templates/footer.php';
	}

	/**
	 * Create a new event based on a past event.
	 */
	public function recreateSave() {
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

		if ($event->Image != "") {
			$fileName = $GLOBALS["beans"]->fileHelper->copyUploadedFile("event", $event->Image, $newEventID);
			$GLOBALS["beans"]->eventModel->updateEventImage($newEventID, $userID, $fileName);
		}

		header('location: ' . URL_WITH_INDEX_FILE . 'events/view/' . $newEventID);
	}

	/**
	 * Display edit event form.
	 * @param integer @eventID Event ID.
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
	 * Save an event.
	 */
	public function save() {
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$eventID = $_POST["eventID"];
		$performUpload = false;
		$oldImage = "";
		$backToEdit = false;

		// Update event if it already exists
		if (is_numeric($eventID)) {
			$event = $GLOBALS["beans"]->eventModel->getEvent($eventID);
			
			// Ensure the action is performed by the host user
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
		}

		// Create a new event
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

		// Upload event image
		if ($performUpload) {
			$result = $GLOBALS["beans"]->fileHelper->uploadFile("image", "event", "jpg,jpeg,png,bmp", "event image", 2097152, $eventID);

			if ($result->fileUploaded) {
				$GLOBALS["beans"]->eventModel->updateEventImage($eventID, $userID, $result->fileName);

				// Delete old event image
				if ($oldImage != "" && $oldImage != $result->fileName) {
					$GLOBALS["beans"]->fileHelper->deleteUploadedFile("event", $oldImage);
				}
			}
			else if ($result->errorMessage != "") {
				$GLOBALS["beans"]->siteHelper->addAlert("danger", $result->errorMessage);
				$backToEdit = true;
			}
		}

		if ($backToEdit) {
			header('location: ' . URL_WITH_INDEX_FILE . 'events/edit/' . $eventID);
		}
		else {
			header('location: ' . URL_WITH_INDEX_FILE . 'events/view/' . $eventID);
		}
	}

	/**
	 * Upload event media.
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
	 * Delete an event.
	 * @param integer $eventID Event ID.
	 */
	public function delete($eventID) {
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$event = $GLOBALS["beans"]->eventModel->getEvent($eventID);

		// Ensure the action is performed by host user
		if ($userID == $event->HostID) {
			$GLOBALS["beans"]->eventModel->deleteMedia($eventID);
			$GLOBALS["beans"]->eventModel->deleteComments($eventID);
			$GLOBALS["beans"]->eventModel->deleteParticipants($eventID);
			$GLOBALS["beans"]->eventModel->deleteEvent($eventID, $userID);
		}

		header('location: ' . URL_WITH_INDEX_FILE . 'events/listHosted');
	}

	/**
	 * Create a comment for an event.
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
	 * Join an event.
	 * @param integer $eventID Event ID.
	 */
	public function join($eventID) {
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$participant = $GLOBALS["beans"]->eventModel->getParticipants($eventID, $userID);

		// Ensure the user is not yet a participant
		if (count($participant) == 0) {
			$GLOBALS["beans"]->eventModel->insertParticipant($eventID, $userID);
		}

		header('location: ' . URL_WITH_INDEX_FILE . 'events/view/' . $eventID);
	}

	/**
	 * Leave an event.
	 * @param integer $eventID Event ID.
	 */
	public function leave($eventID) {
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$event = $GLOBALS["beans"]->eventModel->getEvent($eventID);
		$participant = $GLOBALS["beans"]->eventModel->getParticipants($eventID, $userID);

		// Ensure the user is a participant and not the event host
		if (count($participant) > 0 && $userID != $event->HostID) {
			$GLOBALS["beans"]->eventModel->deleteParticipants($eventID, $userID);
		}

		header('location: ' . URL_WITH_INDEX_FILE . 'events/view/' . $eventID);
	}

	/**
	 * Delete a comment from an event.
	 * @param integer $eventID Event ID.
	 * @param integer $commentID Comment ID.
	 */
	public function deleteComment($eventID, $commentID) {
		// We do not want to accidentally delete all comments in case commentID is blank, so change to a dummy number
		if (!is_numeric($commentID)) {
			$commentID = -1;
		}

		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$comment = $GLOBALS["beans"]->eventModel->getComments($eventID, $commentID);

		// Ensure the comment was created by the user
		if (count($comment) > 0 && $userID == $comment[0]->UserID) {
			$GLOBALS["beans"]->eventModel->deleteComments($eventID, $commentID);
		}

		header('location: ' . URL_WITH_INDEX_FILE . 'events/view/' . $eventID);
	}

	/**
	 * Delete a media from an event.
	 * @param integer $eventID Event ID.
	 * @param integer $mediaID Media ID.
	 */
	public function deleteMedia($eventID, $mediaID) {
		// We do not want to accidentally delete all media in case mediaID is blank, so change to a dummy number
		if (!is_numeric($mediaID)) {
			$mediaID = -1;
		}

		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$media = $GLOBALS["beans"]->eventModel->getMedia($eventID, $mediaID);

		// Ensure the media was uploaded by the user
		if ($userID == $media[0]->UserID) {
			$GLOBALS["beans"]->eventModel->deleteMedia($eventID, $mediaID);
			$GLOBALS["beans"]->fileHelper->deleteUploadedFile("media", $media[0]->Image);
		}

		header('location: ' . URL_WITH_INDEX_FILE . 'events/view/' . $eventID);
	}

	/**
	 * Add notifications for event participants when a new media is uploaded.
	 * @param integer $eventID Event ID.
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