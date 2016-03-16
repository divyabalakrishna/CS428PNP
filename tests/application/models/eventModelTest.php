<?php

include_once(__DIR__ . '/modelTestCase.php');
include_once(__DIR__ . '/../../../src/application/models/eventModel.php');

class EventModelTest extends ModelTestCase
{

	private static $eventModel;

	public static function setUpBeforeClass() {
		static::$eventModel = new EventModel(parent::getPDO());
		parent::setGlobalVariables();
	}

	public function testInsertEvent() {
		$eventID = static::$eventModel->insertEvent(1, 'Mini Basketball', '2 vs 2', '03/05/2016', '3:00 PM', 'Activities and Recreation Center (ARC), 201 E Peabody Dr, Champaign, IL, 61820', 4, 5, 40.100972, -88.236077);

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Event' => array(
				array('EventID' => 3,
						'HostID' => 1,
						'Name' => 'Mini Basketball',
						'Description' => '2 vs 2',
						'Time' => '2016-03-05 15:00:00',
						'Address' => 'Activities and Recreation Center (ARC), 201 E Peabody Dr, Champaign, IL, 61820',
						'Capacity' => 4,
						'Private' => 0,
						'TagID' => 5,
						'Image' => null,
						'Lat' => 40.100972,
						'Lon' => -88.236077)
			)
		)))->getTable('Event');

		$actualTable = $this->getConnection()->createQueryTable('Event', 'SELECT * FROM Event WHERE EventID = ' . $eventID);

		$this->assertEquals(3, $this->getConnection()->getRowCount('Event'));
		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	public function testDeleteEventHosted() {
		static::$eventModel->deleteMedia(1);
		static::$eventModel->deleteComments(1);
		static::$eventModel->deleteParticipants(1);
		static::$eventModel->deleteEvent(1, 1);

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Event' => array()
		)))->getTable('Event');

		$actualTable = $this->getConnection()->createQueryTable('Event', 'SELECT * FROM Event WHERE EventID = 1');

		$this->assertEquals(1, $this->getConnection()->getRowCount('Event'));
		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	public function testDeleteEventNotHosted() {
		// This should not perform the deletion because the user ID is not the host ID
		static::$eventModel->deleteEvent(1, 2);

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Event' => array(
				array('EventID' => 1,
						'HostID' => 1,
						'Name' => 'Casual jogging',
						'Description' => null,
						'Time' => '2015-12-31 17:00:00',
						'Address' => 'Illini Union, 1401 W Green St, Urbana, IL 61801',
						'Capacity' => null,
						'Private' => 0,
						'TagID' => 21,
						'Image' => '1.png',
						'Lat' => 40.109567,
						'Lon' => -88.227213)
			)
		)))->getTable('Event');

		$actualTable = $this->getConnection()->createQueryTable('Event', 'SELECT * FROM Event WHERE EventID = 1');

		$this->assertEquals(2, $this->getConnection()->getRowCount('Event'));
		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	public function testUpdateEventHosted() {
		static::$eventModel->updateEvent(1, 1, 'Test Event', '', '03/05/2016', '3:00 PM', 'Illini Union, 1401 W Green St, Urbana, IL 61801', 10, 1, 40.109567, -88.227213);

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Event' => array(
				array('EventID' => 1,
						'HostID' => 1,
						'Name' => 'Test Event',
						'Description' => null,
						'Time' => '2016-03-05 15:00:00',
						'Address' => 'Illini Union, 1401 W Green St, Urbana, IL 61801',
						'Capacity' => 10,
						'Private' => 0,
						'TagID' => 1,
						'Image' => '1.png',
						'Lat' => 40.109567,
						'Lon' => -88.227213)
			)
		)))->getTable('Event');

		$actualTable = $this->getConnection()->createQueryTable('Event', 'SELECT * FROM Event WHERE EventID = 1');

		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	public function testUpdateEventNotHosted() {
		// This should not perform the update because the user ID is not the host ID
		static::$eventModel->updateEvent(1, 2, 'Test Event', '', '03/05/2016', '3:00 PM', 'Illini Union, 1401 W Green St, Urbana, IL 61801', 10, 1, 40.109567, -88.227213);

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Event' => array(
				array('EventID' => 1,
						'HostID' => 1,
						'Name' => 'Casual jogging',
						'Description' => null,
						'Time' => '2015-12-31 17:00:00',
						'Address' => 'Illini Union, 1401 W Green St, Urbana, IL 61801',
						'Capacity' => null,
						'Private' => 0,
						'TagID' => 21,
						'Image' => '1.png',
						'Lat' => 40.109567,
						'Lon' => -88.227213)
			)
		)))->getTable('Event');

		$actualTable = $this->getConnection()->createQueryTable('Event', 'SELECT * FROM Event WHERE EventID = 1');

		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	public function testGetEvent() {
		$actualObject = static::$eventModel->getEvent(1);

		$expectedObject = new stdClass();
		$expectedObject->EventID = 1;
		$expectedObject->HostID = 1;
		$expectedObject->Name = 'Casual jogging';
		$expectedObject->Description = null;
		$expectedObject->Time = '2015-12-31 17:00:00';
		$expectedObject->Address = 'Illini Union, 1401 W Green St, Urbana, IL 61801';
		$expectedObject->Capacity = null;
		$expectedObject->Private = 0;
		$expectedObject->TagID = 21;
		$expectedObject->Image = '1.png';
		$expectedObject->Lat = 40.109567;
		$expectedObject->Lon = -88.227213;
		$expectedObject->FormattedDate = '12/31/2015';
		$expectedObject->FormattedTime = '05:00 PM';
		$expectedObject->TagName = 'Running';

		$this->assertEquals($expectedObject, $actualObject);
	}

	public function testGetEventHosted() {
		$actualObject = static::$eventModel->getEvent(1, 1);

		$expectedObject = new stdClass();
		$expectedObject->EventID = 1;
		$expectedObject->HostID = 1;
		$expectedObject->Name = 'Casual jogging';
		$expectedObject->Description = null;
		$expectedObject->Time = '2015-12-31 17:00:00';
		$expectedObject->Address = 'Illini Union, 1401 W Green St, Urbana, IL 61801';
		$expectedObject->Capacity = null;
		$expectedObject->Private = 0;
		$expectedObject->TagID = 21;
		$expectedObject->Image = '1.png';
		$expectedObject->Lat = 40.109567;
		$expectedObject->Lon = -88.227213;
		$expectedObject->FormattedDate = '12/31/2015';
		$expectedObject->FormattedTime = '05:00 PM';
		$expectedObject->TagName = 'Running';

		$this->assertEquals($expectedObject, $actualObject);
	}

	public function testGetEventNotHosted() {
		// This should return empty values because the user ID is not the host ID
		$actualObject = static::$eventModel->getEvent(1, 2);

		$expectedObject = new stdClass();
		$expectedObject->EventID = '';
		$expectedObject->HostID = '';
		$expectedObject->Name = '';
		$expectedObject->Description = '';
		$expectedObject->Time = '';
		$expectedObject->Address = '';
		$expectedObject->Capacity = '';
		$expectedObject->Private = '';
		$expectedObject->TagID = '';
		$expectedObject->Image = '';
		$expectedObject->Lat = '';
		$expectedObject->Lon = '';
		$expectedObject->FormattedDate = '';
		$expectedObject->FormattedTime = '';
		$expectedObject->TagName = '';

		$this->assertEquals($expectedObject, $actualObject);
	}

	public function testGetHostedEvents() {
		$actualArray = static::$eventModel->getHostedEvents(1);

		$expectedArray = array();

		$expectedObject = new stdClass();
		$expectedObject->EventID = 1;
		$expectedObject->HostID = 1;
		$expectedObject->Name = 'Casual jogging';
		$expectedObject->Description = null;
		$expectedObject->Time = '2015-12-31 17:00:00';
		$expectedObject->Address = 'Illini Union, 1401 W Green St, Urbana, IL 61801';
		$expectedObject->Capacity = null;
		$expectedObject->Private = 0;
		$expectedObject->TagID = 21;
		$expectedObject->Image = '1.png';
		$expectedObject->Lat = 40.109567;
		$expectedObject->Lon = -88.227213;
		$expectedObject->FormattedDate = '12/31/2015';
		$expectedObject->FormattedTime = '05:00 PM';
		$expectedObject->FormattedDateTime = '12/31/2015 05:00 PM';
		$expectedObject->TagName = 'Running';
		$expectedObject->ParticipantCount = 2;
		$expectedArray[] = $expectedObject;

		$expectedObject = new stdClass();
		$expectedObject->EventID = 2;
		$expectedObject->HostID = 1;
		$expectedObject->Name = 'Badminton Game';
		$expectedObject->Description = "Let's play together!";
		$expectedObject->Time = date('Y-m-d', strtotime('tomorrow')) . ' 17:00:00';
		$expectedObject->Address = 'Activities and Recreation Center (ARC), 201 E Peabody Dr, Champaign, IL, 61820';
		$expectedObject->Capacity = 4;
		$expectedObject->Private = 0;
		$expectedObject->TagID = 3;
		$expectedObject->Image = '2.png';
		$expectedObject->Lat = 40.100972;
		$expectedObject->Lon = -88.236077;
		$expectedObject->FormattedDate = date('m/d/Y', strtotime('tomorrow'));
		$expectedObject->FormattedTime = '05:00 PM';
		$expectedObject->FormattedDateTime = date('m/d/Y', strtotime('tomorrow')) . ' 05:00 PM';
		$expectedObject->TagName = 'Badminton';
		$expectedObject->ParticipantCount = 2;
		$expectedArray[] = $expectedObject;

		$this->assertEquals($expectedArray, $actualArray);
	}

	public function testGetHostedEventsLimit() {
		$actualArray = static::$eventModel->getHostedEvents(1, "", 1);

		$expectedArray = array();

		$expectedObject = new stdClass();
		$expectedObject->EventID = 1;
		$expectedObject->HostID = 1;
		$expectedObject->Name = 'Casual jogging';
		$expectedObject->Description = null;
		$expectedObject->Time = '2015-12-31 17:00:00';
		$expectedObject->Address = 'Illini Union, 1401 W Green St, Urbana, IL 61801';
		$expectedObject->Capacity = null;
		$expectedObject->Private = 0;
		$expectedObject->TagID = 21;
		$expectedObject->Image = '1.png';
		$expectedObject->Lat = 40.109567;
		$expectedObject->Lon = -88.227213;
		$expectedObject->FormattedDate = '12/31/2015';
		$expectedObject->FormattedTime = '05:00 PM';
		$expectedObject->FormattedDateTime = '12/31/2015 05:00 PM';
		$expectedObject->TagName = 'Running';
		$expectedObject->ParticipantCount = 2;
		$expectedArray[] = $expectedObject;

		$this->assertEquals($expectedArray, $actualArray);
	}

	public function testGetHostedEventsFuture() {
		$actualArray = static::$eventModel->getHostedEvents(1, "future");

		$expectedArray = array();

		$expectedObject = new stdClass();
		$expectedObject->EventID = 2;
		$expectedObject->HostID = 1;
		$expectedObject->Name = 'Badminton Game';
		$expectedObject->Description = "Let's play together!";
		$expectedObject->Time = date('Y-m-d', strtotime('tomorrow')) . ' 17:00:00';
		$expectedObject->Address = 'Activities and Recreation Center (ARC), 201 E Peabody Dr, Champaign, IL, 61820';
		$expectedObject->Capacity = 4;
		$expectedObject->Private = 0;
		$expectedObject->TagID = 3;
		$expectedObject->Image = '2.png';
		$expectedObject->Lat = 40.100972;
		$expectedObject->Lon = -88.236077;
		$expectedObject->FormattedDate = date('m/d/Y', strtotime('tomorrow'));
		$expectedObject->FormattedTime = '05:00 PM';
		$expectedObject->FormattedDateTime = date('m/d/Y', strtotime('tomorrow')) . ' 05:00 PM';
		$expectedObject->TagName = 'Badminton';
		$expectedObject->ParticipantCount = 2;
		$expectedArray[] = $expectedObject;

		$this->assertEquals($expectedArray, $actualArray);
	}

	public function testGetHostedEventsPast() {
		$actualArray = static::$eventModel->getHostedEvents(1, "past");

		$expectedArray = array();

		$expectedObject = new stdClass();
		$expectedObject->EventID = 1;
		$expectedObject->HostID = 1;
		$expectedObject->Name = 'Casual jogging';
		$expectedObject->Description = null;
		$expectedObject->Time = '2015-12-31 17:00:00';
		$expectedObject->Address = 'Illini Union, 1401 W Green St, Urbana, IL 61801';
		$expectedObject->Capacity = null;
		$expectedObject->Private = 0;
		$expectedObject->TagID = 21;
		$expectedObject->Image = '1.png';
		$expectedObject->Lat = 40.109567;
		$expectedObject->Lon = -88.227213;
		$expectedObject->FormattedDate = '12/31/2015';
		$expectedObject->FormattedTime = '05:00 PM';
		$expectedObject->FormattedDateTime = '12/31/2015 05:00 PM';
		$expectedObject->TagName = 'Running';
		$expectedObject->ParticipantCount = 2;
		$expectedArray[] = $expectedObject;

		$this->assertEquals($expectedArray, $actualArray);
	}

	public function testGetJoinedEvents() {
		$actualArray = static::$eventModel->getJoinedEvents(2);

		$expectedArray = array();

		$expectedObject = new stdClass();
		$expectedObject->EventID = 1;
		$expectedObject->HostID = 1;
		$expectedObject->Name = 'Casual jogging';
		$expectedObject->Description = null;
		$expectedObject->Time = '2015-12-31 17:00:00';
		$expectedObject->Address = 'Illini Union, 1401 W Green St, Urbana, IL 61801';
		$expectedObject->Capacity = null;
		$expectedObject->Private = 0;
		$expectedObject->TagID = 21;
		$expectedObject->Image = '1.png';
		$expectedObject->Lat = 40.109567;
		$expectedObject->Lon = -88.227213;
		$expectedObject->FormattedDate = '12/31/2015';
		$expectedObject->FormattedTime = '05:00 PM';
		$expectedObject->FormattedDateTime = '12/31/2015 05:00 PM';
		$expectedObject->TagName = 'Running';
		$expectedArray[] = $expectedObject;

		$expectedObject = new stdClass();
		$expectedObject->EventID = 2;
		$expectedObject->HostID = 1;
		$expectedObject->Name = 'Badminton Game';
		$expectedObject->Description = "Let's play together!";
		$expectedObject->Time = date('Y-m-d', strtotime('tomorrow')) . ' 17:00:00';
		$expectedObject->Address = 'Activities and Recreation Center (ARC), 201 E Peabody Dr, Champaign, IL, 61820';
		$expectedObject->Capacity = 4;
		$expectedObject->Private = 0;
		$expectedObject->TagID = 3;
		$expectedObject->Image = '2.png';
		$expectedObject->Lat = 40.100972;
		$expectedObject->Lon = -88.236077;
		$expectedObject->FormattedDate = date('m/d/Y', strtotime('tomorrow'));
		$expectedObject->FormattedTime = '05:00 PM';
		$expectedObject->FormattedDateTime = date('m/d/Y', strtotime('tomorrow')) . ' 05:00 PM';
		$expectedObject->TagName = 'Badminton';
		$expectedArray[] = $expectedObject;

		$this->assertEquals($expectedArray, $actualArray);
	}

	public function testGetJoinedEventsLimit() {
		$actualArray = static::$eventModel->getJoinedEvents(2, "", 1);

		$expectedArray = array();

		$expectedObject = new stdClass();
		$expectedObject->EventID = 1;
		$expectedObject->HostID = 1;
		$expectedObject->Name = 'Casual jogging';
		$expectedObject->Description = null;
		$expectedObject->Time = '2015-12-31 17:00:00';
		$expectedObject->Address = 'Illini Union, 1401 W Green St, Urbana, IL 61801';
		$expectedObject->Capacity = null;
		$expectedObject->Private = 0;
		$expectedObject->TagID = 21;
		$expectedObject->Image = '1.png';
		$expectedObject->Lat = 40.109567;
		$expectedObject->Lon = -88.227213;
		$expectedObject->FormattedDate = '12/31/2015';
		$expectedObject->FormattedTime = '05:00 PM';
		$expectedObject->FormattedDateTime = '12/31/2015 05:00 PM';
		$expectedObject->TagName = 'Running';
		$expectedArray[] = $expectedObject;

		$this->assertEquals($expectedArray, $actualArray);
	}

	public function testGetJoinedEventsFuture() {
		$actualArray = static::$eventModel->getJoinedEvents(2, "future");

		$expectedArray = array();

		$expectedObject = new stdClass();
		$expectedObject->EventID = 2;
		$expectedObject->HostID = 1;
		$expectedObject->Name = 'Badminton Game';
		$expectedObject->Description = "Let's play together!";
		$expectedObject->Time = date('Y-m-d', strtotime('tomorrow')) . ' 17:00:00';
		$expectedObject->Address = 'Activities and Recreation Center (ARC), 201 E Peabody Dr, Champaign, IL, 61820';
		$expectedObject->Capacity = 4;
		$expectedObject->Private = 0;
		$expectedObject->TagID = 3;
		$expectedObject->Image = '2.png';
		$expectedObject->Lat = 40.100972;
		$expectedObject->Lon = -88.236077;
		$expectedObject->FormattedDate = date('m/d/Y', strtotime('tomorrow'));
		$expectedObject->FormattedTime = '05:00 PM';
		$expectedObject->FormattedDateTime = date('m/d/Y', strtotime('tomorrow')) . ' 05:00 PM';
		$expectedObject->TagName = 'Badminton';
		$expectedArray[] = $expectedObject;

		$this->assertEquals($expectedArray, $actualArray);
	}

	public function testGetJoinedEventsPast() {
		$actualArray = static::$eventModel->getJoinedEvents(2, "past");

		$expectedArray = array();

		$expectedObject = new stdClass();
		$expectedObject->EventID = 1;
		$expectedObject->HostID = 1;
		$expectedObject->Name = 'Casual jogging';
		$expectedObject->Description = null;
		$expectedObject->Time = '2015-12-31 17:00:00';
		$expectedObject->Address = 'Illini Union, 1401 W Green St, Urbana, IL 61801';
		$expectedObject->Capacity = null;
		$expectedObject->Private = 0;
		$expectedObject->TagID = 21;
		$expectedObject->Image = '1.png';
		$expectedObject->Lat = 40.109567;
		$expectedObject->Lon = -88.227213;
		$expectedObject->FormattedDate = '12/31/2015';
		$expectedObject->FormattedTime = '05:00 PM';
		$expectedObject->FormattedDateTime = '12/31/2015 05:00 PM';
		$expectedObject->TagName = 'Running';
		$expectedArray[] = $expectedObject;

		$this->assertEquals($expectedArray, $actualArray);
	}

	public function testUpdateEventImageHosted() {
		static::$eventModel->updateEventImage(1, 1, 'abc.jpg');

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Event' => array(
				array('EventID' => 1,
						'HostID' => 1,
						'Name' => 'Casual jogging',
						'Description' => null,
						'Time' => '2015-12-31 17:00:00',
						'Address' => 'Illini Union, 1401 W Green St, Urbana, IL 61801',
						'Capacity' => null,
						'Private' => 0,
						'TagID' => 21,
						'Image' => 'abc.jpg',
						'Lat' => 40.109567,
						'Lon' => -88.227213)
			)
		)))->getTable('Event');

		$actualTable = $this->getConnection()->createQueryTable('Event', 'SELECT * FROM Event WHERE EventID = 1');

		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	public function testUpdateEventImageNotHosted() {
		// This should not perform the update because the user ID is not the host ID
		static::$eventModel->updateEventImage(1, 2, 'abc.jpg');

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Event' => array(
				array('EventID' => 1,
						'HostID' => 1,
						'Name' => 'Casual jogging',
						'Description' => null,
						'Time' => '2015-12-31 17:00:00',
						'Address' => 'Illini Union, 1401 W Green St, Urbana, IL 61801',
						'Capacity' => null,
						'Private' => 0,
						'TagID' => 21,
						'Image' => '1.png',
						'Lat' => 40.109567,
						'Lon' => -88.227213)
			)
		)))->getTable('Event');

		$actualTable = $this->getConnection()->createQueryTable('Event', 'SELECT * FROM Event WHERE EventID = 1');

		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	public function testDeleteParticipants() {
		static::$eventModel->deleteParticipants(1);

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Participant' => array()
		)))->getTable('Participant');

		$actualTable = $this->getConnection()->createQueryTable('Participant', 'SELECT * FROM Participant WHERE EventID = 1');

		$this->assertEquals(2, $this->getConnection()->getRowCount('Participant'));
		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	public function testDeleteParticipantsSpecificUser() {
		static::$eventModel->deleteParticipants(1, 2);

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Participant' => array(
				array('EventID' => 1,
						'UserID' => 1,
						'Invited' => 0)
			)
		)))->getTable('Participant');

		$actualTable = $this->getConnection()->createQueryTable('Participant', 'SELECT * FROM Participant WHERE EventID = 1');

		$this->assertEquals(3, $this->getConnection()->getRowCount('Participant'));
		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	public function testInsertParticipant() {
		static::$eventModel->insertParticipant(2, 3);

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Participant' => array(
				array('EventID' => 2,
						'UserID' => 1,
						'Invited' => 0),
				array('EventID' => 2,
						'UserID' => 2,
						'Invited' => 0),
				array('EventID' => 2,
						'UserID' => 3,
						'Invited' => 0)
			)
		)))->getTable('Participant');

		$actualTable = $this->getConnection()->createQueryTable('Participant', 'SELECT * FROM Participant WHERE EventID = 2');

		$this->assertEquals(5, $this->getConnection()->getRowCount('Participant'));
		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	public function testGetParticipants() {
		$actualArray = static::$eventModel->getParticipants(1);

		$expectedArray = array();

		$expectedObject = new stdClass();
		$expectedObject->EventID = 1;
		$expectedObject->UserID = 1;
		$expectedObject->Invited = 0;
		$expectedObject->FirstName = 'Jane';
		$expectedObject->LastName = 'Doe';
		$expectedObject->Picture = '1.png';
		$expectedArray[] = $expectedObject;

		$expectedObject = new stdClass();
		$expectedObject->EventID = 1;
		$expectedObject->UserID = 2;
		$expectedObject->Invited = 0;
		$expectedObject->FirstName = 'John';
		$expectedObject->LastName = 'Smith';
		$expectedObject->Picture = '2.png';
		$expectedArray[] = $expectedObject;

		$this->assertEquals($expectedArray, $actualArray);
	}

	public function testGetParticipantsSpecificUser() {
		$actualArray = static::$eventModel->getParticipants(2, 2);

		$expectedArray = array();

		$expectedObject = new stdClass();
		$expectedObject->EventID = 2;
		$expectedObject->UserID = 2;
		$expectedObject->Invited = 0;
		$expectedObject->FirstName = 'John';
		$expectedObject->LastName = 'Smith';
		$expectedObject->Picture = '2.png';
		$expectedArray[] = $expectedObject;

		$this->assertEquals($expectedArray, $actualArray);
	}

	public function testGetComments() {
		$actualArray = static::$eventModel->getComments(1);

		$expectedArray = array();

		$expectedObject = new stdClass();
		$expectedObject->CommentID = 1;
		$expectedObject->EventID = 1;
		$expectedObject->UserID = 1;
		$expectedObject->ParentID = 1;
		$expectedObject->Text = 'Hi';
		$expectedObject->FirstName = 'Jane';
		$expectedObject->LastName = 'Doe';
		$expectedObject->Picture = '1.png';
		$expectedArray[] = $expectedObject;

		$expectedObject = new stdClass();
		$expectedObject->CommentID = 3;
		$expectedObject->EventID = 1;
		$expectedObject->UserID = 2;
		$expectedObject->ParentID = 1;
		$expectedObject->Text = 'Hello';
		$expectedObject->FirstName = 'John';
		$expectedObject->LastName = 'Smith';
		$expectedObject->Picture = '2.png';
		$expectedArray[] = $expectedObject;

		$expectedObject = new stdClass();
		$expectedObject->CommentID = 2;
		$expectedObject->EventID = 1;
		$expectedObject->UserID = 2;
		$expectedObject->ParentID = 2;
		$expectedObject->Text = 'Do I need to bring anything?';
		$expectedObject->FirstName = 'John';
		$expectedObject->LastName = 'Smith';
		$expectedObject->Picture = '2.png';
		$expectedArray[] = $expectedObject;

		$this->assertEquals($expectedArray, $actualArray);
	}

	public function testDeleteComments() {
		static::$eventModel->deleteComments(1);

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Comment' => array()
		)))->getTable('Comment');

		$actualTable = $this->getConnection()->createQueryTable('Comment', 'SELECT * FROM Comment WHERE EventID = 1');

		$this->assertEquals(0, $this->getConnection()->getRowCount('Comment'));
		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	public function testDeleteCommentsSpecificComment() {
		static::$eventModel->deleteComments(1, 1);

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Comment' => array(
				array('CommentID' => 2,
						'EventID' => 1,
						'UserID' => 2,
						'ParentID' => 2,
						'Text' => 'Do I need to bring anything?')
			)
		)))->getTable('Comment');

		$actualTable = $this->getConnection()->createQueryTable('Comment', 'SELECT * FROM Comment WHERE EventID = 1');

		$this->assertEquals(1, $this->getConnection()->getRowCount('Comment'));
		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	public function testInsertComment() {
		static::$eventModel->insertComment(2, 2, "", "Stay tuned.");

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Comment' => array(
				array('CommentID' => 4,
						'EventID' => 2,
						'UserID' => 2,
						'ParentID' => 4,
						'Text' => 'Stay tuned.')
			)
		)))->getTable('Comment');

		$actualTable = $this->getConnection()->createQueryTable('Comment', 'SELECT * FROM Comment WHERE EventID = 2');

		$this->assertEquals(4, $this->getConnection()->getRowCount('Comment'));
		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	public function testInsertCommentReply() {
		static::$eventModel->insertComment(1, 1, 2, "Bring your own beverages.");

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Comment' => array(
				array('CommentID' => 1,
						'EventID' => 1,
						'UserID' => 1,
						'ParentID' => 1,
						'Text' => 'Hi'),
				array('CommentID' => 2,
						'EventID' => 1,
						'UserID' => 2,
						'ParentID' => 2,
						'Text' => 'Do I need to bring anything?'),
				array('CommentID' => 3,
						'EventID' => 1,
						'UserID' => 2,
						'ParentID' => 1,
						'Text' => 'Hello'),
				array('CommentID' => 4,
						'EventID' => 1,
						'UserID' => 1,
						'ParentID' => 2,
						'Text' => 'Bring your own beverages.')
			)
		)))->getTable('Comment');

		$actualTable = $this->getConnection()->createQueryTable('Comment', 'SELECT * FROM Comment WHERE EventID = 1');

		$this->assertEquals(4, $this->getConnection()->getRowCount('Comment'));
		$this->assertTablesEqual($expectedTable, $actualTable);
	}

}