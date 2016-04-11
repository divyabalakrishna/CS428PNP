<?php

class Events
{

	public function listHosted()
	{
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");

		$timeType = "future";
		if (array_key_exists("timeType", $_POST))
		{
			$timeType = $_POST["timeType"];
		}

		$events = $GLOBALS["beans"]->eventModel->getHostedEvents($userID, $timeType);

		require APP . 'views/_templates/header.php';
		require APP . 'views/events/index_hosted.php';
		require APP . 'views/_templates/footer.php';
	}

	public function listJoined()
	{
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");

		$timeType = "future";
		if (array_key_exists("timeType", $_POST))
		{
			$timeType = $_POST["timeType"];
		}

		$events = $GLOBALS["beans"]->eventModel->getJoinedEvents($userID, $timeType);

		require APP . 'views/_templates/header.php';
		require APP . 'views/events/index_joined.php';
		require APP . 'views/_templates/footer.php';
	}

    public function listSearch()
	{
         $Lat = $GLOBALS["beans"]->siteHelper->getDefaultLat();
         $Lon = $GLOBALS["beans"]->siteHelper->getDefaultLon();

         if(isset($_COOKIE['latitude'])){
              $Lat = $_COOKIE["latitude"];
         }
         if(isset($_COOKIE['longitude'])){
              $Lon = $_COOKIE["longitude"];
         }
        
         $userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
         $user = $GLOBALS["beans"]->userModel->getProfile($userID);
         
         if(!$user->Radius) $user->Radius = 2;
        
		 $events = $GLOBALS["beans"]->eventModel->getSearchEvents($userID, $user->Radius, $Lat, $Lon);

		 require APP . 'views/_templates/header.php';
		 require APP . 'views/events/index_search.php';
		 require APP . 'views/_templates/footer.php';
	}

    public function genXML()
	{
         $Lat = $GLOBALS["beans"]->siteHelper->getDefaultLat();
         $Lon = $GLOBALS["beans"]->siteHelper->getDefaultLon();

         if(isset($_COOKIE['latitude'])){
              $Lat = $_COOKIE["latitude"];
         }
         if(isset($_COOKIE['longitude'])){
              $Lon = $_COOKIE["longitude"];
         }
        
		 $userID = $GLOBALS["beans"]->siteHelper->getSession("userID");  
         $user = $GLOBALS["beans"]->userModel->getProfile($userID);
        
         if(!$user->Radius) $user->Radius = 2;        
		 $events = $GLOBALS["beans"]->eventModel->getSearchEvents($userID, $user->Radius, $Lat, $Lon);

         require APP . 'views/events/xml.php';
    }

	public function view($eventID)
	{
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
	
	public function recreate($eventID)
	{
		require APP . 'views/_templates/header.php';
		require APP . 'views/events/recreate.php';
		require APP . 'views/_templates/footer.php';
	}

	public function edit($eventID = "")
	{
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$event = $GLOBALS["beans"]->eventModel->getEvent($eventID, $userID);
		$tags = $GLOBALS["beans"]->resourceModel->getTags();

		require APP . 'views/_templates/header.php';
		require APP . 'views/events/edit.php';
		require APP . 'views/_templates/footer.php';
	}

	public function save()
	{
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$eventID = $_POST["eventID"];
		$performUpload = false;
		$oldImage = "";
		$backToEdit = false;

		if (is_numeric($eventID)) {
			$event = $GLOBALS["beans"]->eventModel->getEvent($eventID);

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

		if ($backToEdit) {
			header('location: ' . URL_WITH_INDEX_FILE . 'events/edit/' . $eventID);
		}
		else {
			header('location: ' . URL_WITH_INDEX_FILE . 'events/view/' . $eventID);
		}
	}
	
	public function upload()
	{
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$eventID = $_POST["eventID"];
		$result = $GLOBALS["beans"]->fileHelper->uploadFile("image", "media", "jpg,jpeg,png,bmp", "media image", 2097152, "");

		if ($result->fileUploaded) {
			$GLOBALS["beans"]->eventModel->uploadMedia($eventID, $userID, $result->fileName);
		}
		else if ($result->errorMessage != "") {
			$GLOBALS["beans"]->siteHelper->addAlert("danger", $result->errorMessage);
		}
		header('location: ' . URL_WITH_INDEX_FILE . 'events/view/' . $eventID);
	}

	public function delete($eventID) {
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$event = $GLOBALS["beans"]->eventModel->getEvent($eventID);

		if ($userID == $event->HostID) {
			$GLOBALS["beans"]->eventModel->deleteMedia($eventID);
			$GLOBALS["beans"]->eventModel->deleteComments($eventID);
			$GLOBALS["beans"]->eventModel->deleteParticipants($eventID);
			$GLOBALS["beans"]->eventModel->deleteEvent($eventID, $userID);
		}

		header('location: ' . URL_WITH_INDEX_FILE . 'events/listHosted');
	}

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

	public function join($eventID) {
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$participant = $GLOBALS["beans"]->eventModel->getParticipants($eventID, $userID);

		if (count($participant) == 0) {
			$GLOBALS["beans"]->eventModel->insertParticipant($eventID, $userID);
		}

		header('location: ' . URL_WITH_INDEX_FILE . 'events/view/' . $eventID);
	}

	public function leave($eventID) {
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$event = $GLOBALS["beans"]->eventModel->getEvent($eventID);
		$participant = $GLOBALS["beans"]->eventModel->getParticipants($eventID, $userID);

		if (count($participant) > 0 && $userID != $event->HostID) {
			$GLOBALS["beans"]->eventModel->deleteParticipants($eventID, $userID);
		}

		header('location: ' . URL_WITH_INDEX_FILE . 'events/view/' . $eventID);
	}

}
