<?php

include_once(__DIR__ . '/arrayDataSet.php');

abstract class DBTestCase extends PHPUnit_Extensions_Database_TestCase
{

	private static $pdo = null;

	private $connection = null;

	public static final function getPDO() {
		if (self::$pdo == null) {
			$options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);
			self::$pdo = new PDO('mysql:host=localhost:3306;dbname=plannpla_test', 'plannpla_webapp', 'pnp428', $options);
		}

		return self::$pdo;
	}

	public final function getConnection() {
		if ($this->connection === null) {
			$this->getPDO();
			$this->connection = $this->createDefaultDBConnection(self::$pdo, 'testDB');
		}

		return $this->connection;
	}

	protected final function getDataSet() {
		date_default_timezone_set('UTC');

		return new PHPUnit_ArrayDataSet(array(
			'User' => array(
				$this->createUserObject('Jane', 'Doe', 'jdoe@email.com', '12345', '123-456-7890', '1.png', 5, 120, 'Jane', 'F', '1990-01-01', 'Yes'),	
				$this->createUserObject('John', 'Smith', 'jsmith@email.com', 'abcde', '789-012-3456', '2.png', 5, 60, 'John', 'M', '1985-12-31','Yes'),
				$this->createUserObject('Joe', 'Bloggs', 'joe@email.com', 'password', '456-789-0123', '3.jpg', 5, 120, 'Joe', 'M', '1980-06-15', 'Yes')
			),
			'UserTag' => array(
				$this->createUserTagObject(1, 1),
				$this->createUserTagObject(1, 5),
				$this->createUserTagObject(2, 3)
			),
			'Event' => array(
				$this->createEventObject(null, 1, 'Casual jogging', null, '2015-12-31 17:00:00', 'Illini Union, 1401 W Green St, Urbana, IL 61801', null, 0, 21, '1.png', 40.109567, -88.227213),
				$this->createEventObject(null, 1, 'Badminton Game', "Let's play together!", date('Y-m-d', strtotime('tomorrow')) . ' 17:00:00', 'Activities and Recreation Center (ARC), 201 E Peabody Dr, Champaign, IL, 61820', 4, 0, 3, '2.png', 40.100972, -88.236077)
			),
			'Participant' => array(
				$this->createParticipantObject(1, 1, 0),
				$this->createParticipantObject(1, 2, 0),
				$this->createParticipantObject(2, 1, 0),
				$this->createParticipantObject(2, 2, 0)
			),
			'Comment' => array(
				$this->createCommentObject(null, 1, 1, 1, 'Hi'),
				$this->createCommentObject(null, 1, 2, 2, 'Do I need to bring anything?'),
				$this->createCommentObject(null, 1, 2, 1, 'Hello')
			)
		));
	}

	public function createUserObject($firstName, $lastName, $email, $password, $phone, $picture, $radius, $reminder, $nickName, $gender, $birthDate, $active) {
		return array('FirstName' => $firstName,
				'LastName' => $lastName,
				'Email' => $email,
				'Password' => password_hash($password, PASSWORD_DEFAULT),
				'Phone' => $phone,
				'Picture' => $picture,
				'Radius' => $radius,
				'Reminder' => $reminder,
				'NickName' => $nickName,
				'Gender' => $gender,
				'BirthDate' => $birthDate,
				'Active' => $active);
	}

	public function createUserTagObject($userID, $tagID) {
		return array('UserID' => $userID,
				'TagID' => $tagID);
	}

	public function createEventObject($eventID, $hostID, $name, $description, $datetime, $address, $capacity, $private, $tagID, $image, $latitude, $longitude) {
		$event = array('HostID' => $hostID,
				'Name' => $name,
				'Description' => $description,
				'Time' => $datetime,
				'Address' => $address,
				'Capacity' => $capacity,
				'Private' => $private,
				'TagID' => $tagID,
				'Image' => $image,
				'Lat' => $latitude,
				'Lon' => $longitude);

		if (is_numeric($eventID)) {
			$event = array_merge(array('EventID' => $eventID), $event);
		}

		return $event;
	}

	public function createParticipantObject($eventID, $userID, $invited) {
		return array('EventID' => $eventID,
				'UserID' => $userID,
				'Invited' => $invited);
	}

	public function createCommentObject($commentID, $eventID, $userID, $parentID, $text) {
		$comment = array('EventID' => $eventID,
				'UserID' => $userID,
				'ParentID' => $parentID,
				'Text' => $text);

		if (is_numeric($commentID)) {
			$comment = array_merge(array('CommentID' => $commentID), $comment);
		}

		return $comment;
	}
}