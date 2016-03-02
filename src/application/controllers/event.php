<?php

class Event
{

	public function createEvent()
	{
		require APP . 'views/_templates/header.php';
		require APP . 'views/event/create_event.php';
		require APP . 'views/_templates/footer.php';
	}

}