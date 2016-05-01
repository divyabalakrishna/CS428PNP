<?php

include_once(__DIR__ . '/arrayDataSet.php');

/**
 * This class provides a dataset for the database testing.
 */
abstract class DBTestCase extends PHPUnit_Extensions_Database_TestCase {

	private static $pdo = null;

	private $connection = null;

	/**
	 * Get the PDO database connection.
	 * @return PDO A PDO database connection.
	 */
	public static final function getPDO() {
		// Create a new PDO database connection if it has not yet existed
		if (self::$pdo == null) {
			$options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);
			self::$pdo = new PDO('mysql:host=localhost:3306;dbname=plannpla_test', 'plannpla_webapp', 'pnp428', $options);
		}

		return self::$pdo;
	}

	/**
	 * Get the PHPUnit database connection.
	 * @return object A PHPUnit database connection.
	 */
	public final function getConnection() {
		// Create a new PHPUnit database connection if it has not yet existed
		if ($this->connection === null) {
			$this->getPDO();
			$this->connection = $this->createDefaultDBConnection(self::$pdo, 'testDB');
		}

		return $this->connection;
	}

	/**
	 * Get a mocked data for the database testing.
	 * @return PHPUnit_ArrayDataSet A PHPUnit dataset with mocked data.
	 */
	protected final function getDataSet() {
		date_default_timezone_set('UTC');

		return new PHPUnit_ArrayDataSet(array(
			'User' => array(
				$this->createUserObject(null, 'Jane', 'Doe', 'jdoe@email.com', '12345', '123-456-7890', '1.png', 5, 120, 'Jane', 'F', '1990-01-01', 'Yes'),	
				$this->createUserObject(null, 'John', 'Smith', 'jsmith@email.com', 'abcde', '789-012-3456', '2.png', 5, 60, 'John', 'M', '1985-12-31','Yes'),
				$this->createUserObject(null, 'Joe', 'Bloggs', 'joe@email.com', 'password', '456-789-0123', '3.jpg', 5, 120, 'Joe', 'M', '1980-06-15', 'Yes'),
				$this->createUserObject(null, 'First', 'Last', 'firstlast@email.com', '98765', '678-901-2345', '4.jpg', 5, 60, null, 'F', '1987-04-01', 'abcde12345abcde1')
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
			),
			'Media' => array(
				$this->createMediaObject(null, 1, 2, 'img_123.jpg'),
				$this->createMediaObject(null, 1, 1, 'members.jpg'),
				$this->createMediaObject(null, 2, 1, 'img_562.png')
			)
		));
	}

	/**
	 * Create a user record in an array format.
	 * @param integer $userID User ID.
	 * @param string $firstName First name.
	 * @param string $lastName Last name.
	 * @param string $email Email address.
	 * @param string $password Password.
	 * @param string $phone Phone number.
	 * @param string $picture Profile picture file name.
	 * @param integer $radius Radius setting.
	 * @param integer $reminder Reminder setting.
	 * @param string $nickName Nick name.
	 * @param string $gender Gender.
	 * @param string $birthDate Birthdate.
	 * @param string $active Activation code.
	 * @return array User record.
	 */
	public function createUserObject($userID, $firstName, $lastName, $email, $password, $phone, $picture, $radius, $reminder, $nickName, $gender, $birthDate, $active) {
		$user = array('FirstName' => $firstName,
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

		if (is_numeric($userID)) {
			$user = array_merge(array('UserID' => $userID), $user);
		}

		return $user;
	}

	/**
	 * Create a user tag record in an array format.
	 * @param integer $userID User ID.
	 * @param integer $tagID Tag ID.
	 * @return array User tag record.
	 */
	public function createUserTagObject($userID, $tagID) {
		return array('UserID' => $userID,
				'TagID' => $tagID);
	}

	/**
	 * Create an event record in an array format.
	 * @param integer $eventID Event ID.
	 * @param integer $hostID Host user ID.
	 * @param string $name Name.
	 * @param string $description Description.
	 * @param string $datetime Date and time.
	 * @param string $address Address.
	 * @param integer $capacity Capacity.
	 * @param integer $private Private flag.
	 * @param integer $tagID Tag ID.
	 * @param string $image Event image file name.
	 * @param double $latitude Latitude.
	 * @param double $longitude Longitude.
	 * @return array Event record.
	 */
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

	/**
	 * Create an event record in an array format.
	 * @param unknown $eventID
	 * @param unknown $userID
	 * @param unknown $invited
	 * @return array User record.
	 */
	public function createParticipantObject($eventID, $userID, $invited) {
		return array('EventID' => $eventID,
				'UserID' => $userID,
				'Invited' => $invited);
	}

	/**
	 * Create an event comment record in an array format.
	 * @param integer $commentID Comment ID.
	 * @param integer $eventID Event ID.
	 * @param integer $userID User ID.
	 * @param integer $parentID Parent comment ID.
	 * @param string $text Comment text.
	 * @return array Event comment record.
	 */
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

	/**
	 * Create an event media record in an array format.
	 * @param integer $mediaID Media ID.
	 * @param integer $eventID Event ID.
	 * @param integer $userID User ID.
	 * @param string $image Media file name.
	 * @return array Event media record.
	 */
	public function createMediaObject($mediaID, $eventID, $userID, $image) {
		$media = array('MediaID' => $mediaID,
				'EventID' => $eventID,
				'UserID' => $userID,
				'Image' => $image);

		if (is_numeric($mediaID)) {
			$media = array_merge(array('MediaID' => $mediaID), $media);
		}

		return $media;
	}

}