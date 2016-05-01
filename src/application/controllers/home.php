<?php

/**
 * This class acts as a controller for home module.
 */
class Home {

	/**
	 * Display home page.
	 */	
	public function index() {
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");

		// If the user is logged in
		if (is_numeric($userID)) {
			if ($GLOBALS["beans"]->userModel->isActive($userID)->Active != 'Yes') {
				$user = $GLOBALS["beans"]->userModel->getProfile($userID);
				$active = "";
				require APP . 'views/_templates/header.php';
				require APP . 'views/user/activation.php';
				require APP . 'views/_templates/footer.php';
			}
			else {
				$hostedEvents = $GLOBALS["beans"]->eventModel->getHostedEvents($userID, "future", 4);
				$joinedEvents = $GLOBALS["beans"]->eventModel->getJoinedEvents($userID, "future", 5);
				$pastEvents = $GLOBALS["beans"]->eventModel->getPastEvents();
				$joinableEvents = $GLOBALS["beans"]->eventModel->getFeed($userID);

				require APP . 'views/_templates/header.php';
				require APP . 'views/home/index.php';
				require APP . 'views/_templates/footer.php';
			}
		}
		else {
			$cheat = 0;
			require APP . 'views/_templates/header.php';
			require APP . 'views/index.php';
			require APP . 'views/_templates/footer.php';
		}
	}

}