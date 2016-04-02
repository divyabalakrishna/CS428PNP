<?php

/**
 * Class Home
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class Home
{

	public function index()
	{
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");

		if (is_numeric($userID)) {
            if ($GLOBALS["beans"]->userModel->isActive($userID)->Active != 'Yes')
            {
                $user = $GLOBALS["beans"]->userModel->getProfile($userID);
                $active = "";
                require APP . 'views/_templates/header.php';
                require APP . 'views/user/activation.php';
                require APP . 'views/_templates/footer.php';                
            }
            else
            {
                $hostedEvents = $GLOBALS["beans"]->eventModel->getHostedEvents($userID, "future", 4);
                $joinedEvents = $GLOBALS["beans"]->eventModel->getJoinedEvents($userID, "future", 5);

                require APP . 'views/_templates/header.php';
                require APP . 'views/home/index.php';
                require APP . 'views/_templates/footer.php';
            }
		}
		else {
            $cheat=0;
			require APP . 'views/_templates/header.php';
			require APP . 'views/index.php';
			require APP . 'views/_templates/footer.php';
		}
	}

}