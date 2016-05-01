<?php

	/**
	 * Generate notifications using crontab.
	 * This file is separated from the TINY framework because it needs to be called directly from crontab.
	 * Put this line into crontab to create notif_log file in the appropriate directory:
	 * crontab -e /usr/local/bin/php /home/plannplay/public_html/application/helpers/notifGen.php >> /home/plannplay/public_html/notif_log
	 */

	// DIRECTORY_SEPARATOR adds a slash to the end of the path
	define('ROOT', __DIR__ . DIRECTORY_SEPARATOR);
	// set a constant that holds the project's "application" folder, like "/var/www/application".
	define('APP', dirname(__DIR__));
	
	define('DB_TYPE', 'mysql');
	define('DB_HOST', 'localhost:3306');
	define('DB_NAME', 'plannpla_db');
	define('DB_USER', 'plannpla_webapp');
	define('DB_PASS', 'pnp428');
	$options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);
	$db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS, $options);

	/**
	 * Execute a read query and get all results.
	 * @param object $db A PDO database connection.
	 * @param string $sql SQL statement.
	 * @param array $parameters Parameter values.
	 * @return array Query result.
	 */
	function getAllRows($db, $sql, $parameters = "") {
		$query = $db->prepare($sql);

		if ($parameters == "") {
			$query->execute();
		}
		else {
			$query->execute($parameters);
		}

		return $query->fetchAll();
	}

	/**
	 * Execute a write query.
	 * @param object $db A PDO database connection.
	 * @param string $sql SQL statement.
	 * @param array $parameters Parameter values.
	 * @return integer|void Record ID if the query is an insert query, no return value otherwise.
	 */
	function executeWriteQuery($db, $sql, $parameters) {
		foreach ($parameters as $parameterKey => $parameterValue) {
			if (!is_numeric($parameterValue) && $parameterValue == "") {
				$parameters[$parameterKey] = null;
			}
		}

		$query = $db->prepare($sql);
		$query->execute($parameters);

		return $db->lastInsertId();
	}

	/**
	 * Get all user IDs.
	 * @param object $db A PDO database connection.
	 * @return array Query result.
	 */
	function getAllUser($db) {
		$sql = "SELECT UserID
				FROM User";

		$parameters = "";

		return getAllRows($db, $sql, $parameters);
	}

	/**
	 * Get events that are joined by a user.
	 * @param object $db A PDO database connection.
	 * @param integer $userID User ID.
	 * @param integer $hour Number of hour before events start.
	 * @param string $check
	 * @return array Query result.
	 */
	function getJoinedEvents($db, $userID, $hour="", $check = "") {
		$sql = "SELECT Event.*,
					Tag.Name AS TagName,
					TIMEDIFF(Event.Time, now()) AS TimeDiff, 
					TIMESTAMPDIFF(HOUR,Event.Time, now()) AS HourDiff,
					MINUTE(TIMEDIFF(Event.Time, now())) AS MinDiff
				FROM Event
				INNER JOIN Participant ON Participant.EventID = Event.EventID
				LEFT JOIN Tag ON Tag.TagID = Event.TagID
				WHERE Participant.UserID = :userID";

		if (is_numeric($hour) && !is_numeric($check)) {
			$sql .= " AND TIMESTAMPDIFF(HOUR,Event.Time, now()) = -" . $hour;
			$sql .= " AND MINUTE(TIMEDIFF(Event.Time, now())) = 0 ";
		}

		$sql .= " ORDER BY Event.Time";

		$parameters = array(":userID" => $userID);

		return getAllRows($db, $sql, $parameters);
	}

	/**
	 * Insert a notification record.
	 * @param object $db A PDO database connection.
	 * @param integer $userID User ID.
	 * @param integer $eventID Event ID.
	 * @param string $msg Notification message.
	 * @param string $urlLink Click URL.
	 * @param string $imgLink Notification image URL.
	 * @return integer Notification ID.
	 */
	function insertNotif($db, $userID, $eventID, $msg, $urlLink, $imgLink) {
		$sql = "INSERT INTO Notification (UserID, EventID, Message, Time, UrlLink, ImgLink)
				VALUES (:userID, :eventID, :msg, now(), :urlLink, :imgLink)";

		$parameters = array(
				":userID" => $userID,
				":eventID" => $eventID,
				":msg" => $msg,
				":urlLink" => $urlLink,
				":imgLink" => $imgLink
		);

		return executeWriteQuery($db, $sql, $parameters);
	}

	/**
	 * Generate notifications for all users.
	 * @param object $db A PDO database connection.
	 * @param integer $hour Number of hour before events start.
	 * @param string $check
	 */
	function genNotifications($db, $hour, $check = "") {
		$users = getAllUser($db);

		foreach ($users as $user) { 
			$events = getJoinedEvents($db, $user->UserID, $hour, $check);

			foreach ($events as $event) {
				if ($event->Image) {
					$imgLink = "/uploads/event/" . $event->Image;
				}
				else {
					$imgLink = "/public/img/sports/" . $event->TagName . ".png";
				}

				insertNotif(
						$db,
						$user->UserID,
						$event->EventID,
						$event->Name . " begins in " . $hour . " hours",
						"/events/view/" . $event->EventID,
						$imgLink
				);
			}
		}
	}

	genNotifications($db,1);
	genNotifications($db,24);
?>