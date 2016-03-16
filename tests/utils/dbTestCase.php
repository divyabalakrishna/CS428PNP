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
				array('FirstName' => 'Jane',
						'LastName' => 'Doe',
						'Email' => 'jdoe@email.com',
						'Password' => password_hash('12345', PASSWORD_DEFAULT),
						'Phone' => '123-456-7890',
						'Picture' => '1.png',
						'Radius' => 5,
						'Reminder' => 120,
						'NickName' => 'Jane',
						'Gender' => 'F',
						'BirthDate' => '1990-01-01'),	
				array('FirstName' => 'John',
						'LastName' => 'Smith',
						'Email' => 'jsmith@email.com',
						'Password' => password_hash('abcde', PASSWORD_DEFAULT),
						'Phone' => '789-012-3456',
						'Picture' => '2.png',
						'Radius' => 5,
						'Reminder' => 60,
						'NickName' => 'John',
						'Gender' => 'M',
						'BirthDate' => '1985-12-31'),
				array('FirstName' => 'Joe',
						'LastName' => 'Bloggs',
						'Email' => 'joe@email.com',
						'Password' => password_hash('password', PASSWORD_DEFAULT),
						'Phone' => '456-789-0123',
						'Picture' => '3.jpg',
						'Radius' => 5,
						'Reminder' => 120,
						'NickName' => 'Joe',
						'Gender' => 'M',
						'BirthDate' => '1980-06-15')
			),
			'UserTag' => array(
				array('UserID' => 1,
						'TagID' => 1),
				array('UserID' => 1,
						'TagID' => 5),
				array('UserID' => 2,
						'TagID' => 3)
			),
			'Event' => array(
				array('HostID' => 1,
						'Name' => 'Casual jogging',
						'Description' => null,
						'Time' => '2015-12-31 17:00:00',
						'Address' => 'Illini Union, 1401 W Green St, Urbana, IL 61801',
						'Capacity' => null,
						'Private' => 0,
						'TagID' => 21,
						'Image' => '1.png',
						'Lat' => 40.109567,
						'Lon' => -88.227213),
				array('HostID' => 1,
						'Name' => 'Badminton Game',
						'Description' => "Let's play together!",
						'Time' => date('Y-m-d', strtotime('tomorrow')) . ' 17:00:00',
						'Address' => 'Activities and Recreation Center (ARC), 201 E Peabody Dr, Champaign, IL, 61820',
						'Capacity' => 4,
						'Private' => 0,
						'TagID' => 3,
						'Image' => '2.png',
						'Lat' => 40.100972,
						'Lon' => -88.236077)
			),
			'Participant' => array(
				array('EventID' => 1,
						'UserID' => 1,
						'Invited' => 0),
				array('EventID' => 1,
						'UserID' => 2,
						'Invited' => 0),
				array('EventID' => 2,
						'UserID' => 1,
						'Invited' => 0),
				array('EventID' => 2,
						'UserID' => 2,
						'Invited' => 0)
			),
			'Comment' => array(
				array('EventID' => 1,
						'UserID' => 1,
						'ParentID' => 1,
						'Text' => 'Hi'),
				array('EventID' => 1,
						'UserID' => 2,
						'ParentID' => 2,
						'Text' => 'Do I need to bring anything?'),
				array('EventID' => 1,
						'UserID' => 2,
						'ParentID' => 1,
						'Text' => 'Hello')
			)
		));
	}

}