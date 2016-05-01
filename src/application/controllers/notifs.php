<?php

class Notifs {

	/**
	 * notification main page
	 */
	public function index() {
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$events = $GLOBALS["beans"]->notifModel->getNotifications($userID,"");

		require APP . 'views/_templates/header.php';
		require APP . 'views/notifs/index.php';
		require APP . 'views/_templates/footer.php';
	}

	/**
	 * generate notification based on time (1hour/24hour) before it start
	 * @param integer $hour
	 * @param string $check
	 */
	public function genNotifications($hour, $check="") {

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

				//Insert Notifications
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
	 * update notification read flag
	 * @param integer $notifID
	 */
	public function updateFlag($notifID) {
		$GLOBALS["beans"]->notifModel->updateFlag($notifID);
	}

}