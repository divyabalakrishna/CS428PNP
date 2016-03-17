<?php

class Notifs
{

	public function index()
	{
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$events = $GLOBALS["beans"]->notifModel->getNotifications($userID,"");

		require APP . 'views/_templates/header.php';
		require APP . 'views/notifs/index.php';
		require APP . 'views/_templates/footer.php';
	}

    public function genNotifications($hour, $check="")
    {

        $users = $GLOBALS["beans"]->userModel->getAllUser();

        foreach ($users as $user) { 

            echo "asd" . $user->UserID . "<br>";
    		$events = $GLOBALS["beans"]->notifModel->getJoinedEvents($user->UserID, $hour, $check);

            foreach ($events as $event) { 
        
                if($event->Image)
                    $imgLink = "/uploads/event/" . $event->Image;
                else 
                    $imgLink = "/public/img/sports/" . $event->TagName . ".png";
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
}
