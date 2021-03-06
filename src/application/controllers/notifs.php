<?php

/**
 * This class acts as a controller for notification module.
 */
class Notifs {

	/**
	 * Display a list of notifications for a user.
	 */
	public function index() {
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$events = $GLOBALS["beans"]->notifModel->getNotifications($userID,"");

		require APP . 'views/_templates/header.php';
		require APP . 'views/notifs/index.php';
		require APP . 'views/_templates/footer.php';
	}

	/**
	 * Generate notifications for event participants.
	 * @param integer $hour Number of hour before events start.
	 * @param string $check
	 */
	public function genNotifications($hour, $check = "") {

		$users = $GLOBALS["beans"]->userModel->getAllUserIDs();

		foreach ($users as $user) { 
			echo "notif " . $user->UserID . "<br>";
			$events = $GLOBALS["beans"]->notifModel->getJoinedEvents($user->UserID, $hour, $check);

			foreach ($events as $event) { 
				if ($event->Image) {
					$imgLink = "/uploads/event/" . $event->Image;
				}
				else {
					$imgLink = "/public/img/sports/" . $event->TagName . ".png";
				}

				$GLOBALS["beans"]->notifModel->insertNotif(
						$user->UserID,
						$event->EventID,
						$event->Name . " begins in " . $hour . " hours",
						"/events/view/" . $event->EventID,
						$imgLink
				);
			}
		}
	}

	/**
	 * Update the read flag for a notification.
	 * @param integer $notifID Notification ID.
	 */
	public function updateFlag($notifID) {
		$GLOBALS["beans"]->notifModel->updateFlag($notifID);
	}

}