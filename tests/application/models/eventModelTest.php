<?php

include_once(__DIR__ . '/modelTestCase.php');
include_once(__DIR__ . '/../../../src/application/models/eventModel.php');

/**
 * This class provides unit tests for EventModel.
 */
class EventModelTest extends ModelTestCase {

	private static $eventModel;

	/**
	 * Initialize required variables once for all tests.
	 */
	public static function setUpBeforeClass() {
		static::$eventModel = new EventModel(parent::getPDO());
		parent::setGlobalVariables();
	}

	/**
	 * Test the normal usage for insertEvent function.
	 */
	public function testInsertEvent() {
		$eventID = static::$eventModel->insertEvent(1, 'Mini Basketball', '2 vs 2', '03/05/2016', '3:00 PM', 'Activities and Recreation Center (ARC), 201 E Peabody Dr, Champaign, IL, 61820', 4, 5, 40.100972, -88.236077);

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Event' => array(
				parent::createEventObject(3, 1, 'Mini Basketball', '2 vs 2', '2016-03-05 15:00:00', 'Activities and Recreation Center (ARC), 201 E Peabody Dr, Champaign, IL, 61820', 4, 0, 5, null, 40.100972, -88.236077)
			)
		)))->getTable('Event');

		$actualTable = $this->getConnection()->createQueryTable('Event', 'SELECT * FROM Event WHERE EventID = ' . $eventID);

		$this->assertEquals(3, $this->getConnection()->getRowCount('Event'));
		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	/**
	 * Test deleteEvent function when the user is the event host.
	 */
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

	/**
	 * Test deleteEvent function when the user is not the event host.
	 */
	public function testDeleteEventNotHosted() {
		// This should not perform the deletion because the user ID is not the host ID
		static::$eventModel->deleteEvent(1, 2);

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Event' => array(
				parent::createEventObject(1, 1, 'Casual jogging', null, '2015-12-31 17:00:00', 'Illini Union, 1401 W Green St, Urbana, IL 61801', null, 0, 21, '1.png', 40.109567, -88.227213)
			)
		)))->getTable('Event');

		$actualTable = $this->getConnection()->createQueryTable('Event', 'SELECT * FROM Event WHERE EventID = 1');

		$this->assertEquals(2, $this->getConnection()->getRowCount('Event'));
		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	/**
	 * Test updateEvent function when the user is the event host.
	 */
	public function testUpdateEventHosted() {
		static::$eventModel->updateEvent(1, 1, 'Test Event', '', '03/05/2016', '3:00 PM', 'Illini Union, 1401 W Green St, Urbana, IL 61801', 10, 1, 40.109567, -88.227213);

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Event' => array(
				parent::createEventObject(1, 1, 'Test Event', null, '2016-03-05 15:00:00', 'Illini Union, 1401 W Green St, Urbana, IL 61801', 10, 0, 1, '1.png', 40.109567, -88.227213)
			)
		)))->getTable('Event');

		$actualTable = $this->getConnection()->createQueryTable('Event', 'SELECT * FROM Event WHERE EventID = 1');

		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	/**
	 * Test updateEvent function when the user is not the event host.
	 */
	public function testUpdateEventNotHosted() {
		// This should not perform the update because the user ID is not the host ID
		static::$eventModel->updateEvent(1, 2, 'Test Event', '', '03/05/2016', '3:00 PM', 'Illini Union, 1401 W Green St, Urbana, IL 61801', 10, 1, 40.109567, -88.227213);

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Event' => array(
				parent::createEventObject(1, 1, 'Casual jogging', null, '2015-12-31 17:00:00', 'Illini Union, 1401 W Green St, Urbana, IL 61801', null, 0, 21, '1.png', 40.109567, -88.227213)
			)
		)))->getTable('Event');

		$actualTable = $this->getConnection()->createQueryTable('Event', 'SELECT * FROM Event WHERE EventID = 1');

		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	/**
	 * Test the normal usage of getEvent function.
	 */
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
		$expectedObject->HostFirstName = 'Jane';
		$expectedObject->HostLastName = 'Doe';

		$this->assertEquals($expectedObject, $actualObject);
	}

	/**
	 * Test getEvent function when the user is the event host.
	 */
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
		$expectedObject->HostFirstName = 'Jane';
		$expectedObject->HostLastName = 'Doe';

		$this->assertEquals($expectedObject, $actualObject);
	}

	/**
	 * Test getEvent function when the user is not the event host.
	 */
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
		$expectedObject->HostFirstName = '';
		$expectedObject->HostLastName = '';

		$this->assertEquals($expectedObject, $actualObject);
	}

	/**
	 * Test the normal usage for getHostedEvents function.
	 */
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

	/**
	 * Test getHostedEvents function with limited number of results.
	 */
	public function testGetHostedEventsLimit() {
		$actualArray = static::$eventModel->getHostedEvents(1, '', 1);

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

	/**
	 * Test getHostedEvents function for future events.
	 */
	public function testGetHostedEventsFuture() {
		$actualArray = static::$eventModel->getHostedEvents(1, 'future');

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

	/**
	 * Test getHostedEvents function for past events.
	 */
	public function testGetHostedEventsPast() {
		$actualArray = static::$eventModel->getHostedEvents(1, 'past');

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

	/**
	 * Test the normal usage for getJoinedEvents function.
	 */
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

	/**
	 * Test getJoinedEvents function with limited number of results.
	 */
	public function testGetJoinedEventsLimit() {
		$actualArray = static::$eventModel->getJoinedEvents(2, '', 1);

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

	/**
	 * Test getJoinedEvents function for future events.
	 */
	public function testGetJoinedEventsFuture() {
		$actualArray = static::$eventModel->getJoinedEvents(2, 'future');

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

	/**
	 * Test getJoinedEvents function for past events.
	 */
	public function testGetJoinedEventsPast() {
		$actualArray = static::$eventModel->getJoinedEvents(2, 'past');

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

	/**
	 * Test updateEventImage function when the user is the event host.
	 */
	public function testUpdateEventImageHosted() {
		static::$eventModel->updateEventImage(1, 1, 'abc.jpg');

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Event' => array(
				parent::createEventObject(1, 1, 'Casual jogging', null, '2015-12-31 17:00:00', 'Illini Union, 1401 W Green St, Urbana, IL 61801', null, 0, 21, 'abc.jpg', 40.109567,-88.227213)
			)
		)))->getTable('Event');

		$actualTable = $this->getConnection()->createQueryTable('Event', 'SELECT * FROM Event WHERE EventID = 1');

		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	/**
	 * Test updateEventImage function when the user is not the event host.
	 */
	public function testUpdateEventImageNotHosted() {
		// This should not perform the update because the user ID is not the host ID
		static::$eventModel->updateEventImage(1, 2, 'abc.jpg');

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Event' => array(
				parent::createEventObject(1, 1, 'Casual jogging', null, '2015-12-31 17:00:00', 'Illini Union, 1401 W Green St, Urbana, IL 61801', null, 0, 21, '1.png', 40.109567, -88.227213)
			)
		)))->getTable('Event');

		$actualTable = $this->getConnection()->createQueryTable('Event', 'SELECT * FROM Event WHERE EventID = 1');

		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	/**
	 * Test deleteParticipants function for an event.
	 */
	public function testDeleteParticipants() {
		static::$eventModel->deleteParticipants(1);

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Participant' => array()
		)))->getTable('Participant');

		$actualTable = $this->getConnection()->createQueryTable('Participant', 'SELECT * FROM Participant WHERE EventID = 1');

		$this->assertEquals(2, $this->getConnection()->getRowCount('Participant'));
		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	/**
	 * Test deleteParticipants function for a specific user.
	 */
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

	/**
	 * Test the normal usage for insertParticipant function.
	 */
	public function testInsertParticipant() {
		static::$eventModel->insertParticipant(2, 3);

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Participant' => array(
				parent::createParticipantObject(2, 1, 0),
				parent::createParticipantObject(2, 2, 0),
				parent::createParticipantObject(2, 3, 0)
			)
		)))->getTable('Participant');

		$actualTable = $this->getConnection()->createQueryTable('Participant', 'SELECT * FROM Participant WHERE EventID = 2');

		$this->assertEquals(5, $this->getConnection()->getRowCount('Participant'));
		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	/**
	 * Test getParticipants function for an event.
	 */
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

	/**
	 * Test getParticipants function for a specific user.
	 */
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

	/**
	 * Test the normal usage for getComments function.
	 */
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

	/**
	 * Test deleteComments function for an event.
	 */
	public function testDeleteComments() {
		static::$eventModel->deleteComments(1);

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Comment' => array()
		)))->getTable('Comment');

		$actualTable = $this->getConnection()->createQueryTable('Comment', 'SELECT * FROM Comment WHERE EventID = 1');

		$this->assertEquals(0, $this->getConnection()->getRowCount('Comment'));
		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	/**
	 * Test deleteComments function for a specific comment.
	 */
	public function testDeleteCommentsSpecificComment() {
		static::$eventModel->deleteComments(1, 1);

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Comment' => array(
				parent::createCommentObject(2, 1, 2, 2, 'Do I need to bring anything?')
			)
		)))->getTable('Comment');

		$actualTable = $this->getConnection()->createQueryTable('Comment', 'SELECT * FROM Comment WHERE EventID = 1');

		$this->assertEquals(1, $this->getConnection()->getRowCount('Comment'));
		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	/**
	 * Test insertComment function for a new comment.
	 */
	public function testInsertComment() {
		static::$eventModel->insertComment(2, 2, '', 'Stay tuned.');

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Comment' => array(
				parent::createCommentObject(4, 2, 2, 4, 'Stay tuned.')
			)
		)))->getTable('Comment');

		$actualTable = $this->getConnection()->createQueryTable('Comment', 'SELECT * FROM Comment WHERE EventID = 2');

		$this->assertEquals(4, $this->getConnection()->getRowCount('Comment'));
		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	/**
	 * Test insertComment function for a reply comment.
	 */
	public function testInsertCommentReply() {
		static::$eventModel->insertComment(1, 1, 2, 'Bring your own beverages.');

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Comment' => array(
				parent::createCommentObject(1, 1, 1, 1, 'Hi'),
				parent::createCommentObject(2, 1, 2, 2, 'Do I need to bring anything?'),
				parent::createCommentObject(3, 1, 2, 1, 'Hello'),
				parent::createCommentObject(4, 1, 1, 2, 'Bring your own beverages.')
			)
		)))->getTable('Comment');

		$actualTable = $this->getConnection()->createQueryTable('Comment', 'SELECT * FROM Comment WHERE EventID = 1');

		$this->assertEquals(4, $this->getConnection()->getRowCount('Comment'));
		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	/**
	 * Test deleteMedia function for an event.
	 */
	public function testDeleteMedia() {
		static::$eventModel->deleteMedia(1);

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Media' => array()
		)))->getTable('Media');

		$actualTable = $this->getConnection()->createQueryTable('Media', 'SELECT * FROM Media WHERE EventID = 1');

		$this->assertEquals(1, $this->getConnection()->getRowCount('Media'));
		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	/**
	 * Test deleteMedia function for a specific media.
	 */
	public function testDeleteMediaSpecificMedia() {
		static::$eventModel->deleteMedia(1, 1);

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Media' => array(
				parent::createMediaObject(2, 1, 1, 'members.jpg')
			)
		)))->getTable('Media');

		$actualTable = $this->getConnection()->createQueryTable('Media', 'SELECT * FROM Media WHERE EventID = 1');

		$this->assertEquals(2, $this->getConnection()->getRowCount('Media'));
		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	/**
	 * Test getMedia function for an event.
	 */
	public function testGetMedia() {
		$actualArray = static::$eventModel->getMedia(1);

		$expectedArray = array();
		$expectedArray[] = (object)parent::createMediaObject(1, 1, 2, 'img_123.jpg');
		$expectedArray[] = (object)parent::createMediaObject(2, 1, 1, 'members.jpg');

		$this->assertEquals($expectedArray, $actualArray);
	}

	/**
	 * Test getMedia function for a specific media.
	 */
	public function testGetMediaSpecificMedia() {
		$actualArray = static::$eventModel->getMedia(1, 2);

		$expectedArray = array();
		$expectedArray[] = (object)parent::createMediaObject(2, 1, 1, 'members.jpg');

		$this->assertEquals($expectedArray, $actualArray);
	}

	/**
	 * Test the normal usage for insertMedia function.
	 */
	public function testInsertMedia() {
		static::$eventModel->insertMedia(2, 2, 'photo.jpg');

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Media' => array(
				parent::createMediaObject(3, 2, 1, 'img_562.png'),
				parent::createMediaObject(4, 2, 2, 'photo.jpg')
			)
		)))->getTable('Media');

		$actualTable = $this->getConnection()->createQueryTable('Media', 'SELECT * FROM Media WHERE EventID = 2');

		$this->assertEquals(4, $this->getConnection()->getRowCount('Media'));
		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	/**
	 * Test the normal usage for copyEvent function.
	 */
	public function testCopyEvent() {
		$eventID = static::$eventModel->copyEvent(2, '04/11/2016', '10:00 AM');

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Event' => array(
				parent::createEventObject(3, 1, 'Badminton Game', "Let's play together!", '2016-04-11 10:00:00', 'Activities and Recreation Center (ARC), 201 E Peabody Dr, Champaign, IL, 61820', 4, 0, 3, null, 40.100972, -88.236077)
			)
		)))->getTable('Event');

		$actualTable = $this->getConnection()->createQueryTable('Event', 'SELECT * FROM Event WHERE EventID = ' . $eventID);

		$this->assertEquals(3, $this->getConnection()->getRowCount('Event'));
		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	/**
	 * Test the normal usage for copyParticipant function.
	 */
	public function testCopyParticipant() {
		$eventID = static::$eventModel->copyEvent(2, '04/11/2016', '10:00 AM');
		static::$eventModel->copyParticipant(2, $eventID);

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Participant' => array(
				parent::createParticipantObject($eventID, 1, 0),
				parent::createParticipantObject($eventID, 2, 0)
			)
		)))->getTable('Participant');

		$actualTable = $this->getConnection()->createQueryTable('Participant', 'SELECT * FROM Participant WHERE EventID = ' . $eventID);

		$this->assertEquals(6, $this->getConnection()->getRowCount('Participant'));
		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	/**
	 * Test copyParticipant function when the users have already joined the event.
	 */
	public function testCopyParticipantDuplicate() {
		// This should not insert any records since the participants already joined the event
		static::$eventModel->copyParticipant(1, 2);

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Participant' => array(
				parent::createParticipantObject(2, 1, 0),
				parent::createParticipantObject(2, 2, 0)
			)
		)))->getTable('Participant');

		$actualTable = $this->getConnection()->createQueryTable('Participant', 'SELECT * FROM Participant WHERE EventID = 2');

		$this->assertEquals(4, $this->getConnection()->getRowCount('Participant'));
		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	/**
	 * Test getSearchEvents function for future events.
	 */
	public function testGetSearchEventsFuture() {
		$actualArray = static::$eventModel->getSearchEvents(1, 5, 40.1, -88.2, '', false);

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
		$expectedObject->FormattedDateTime = date('m/d/Y', strtotime('tomorrow')) . ' 05:00 PM';
		$expectedObject->TagName = 'Badminton';
		$expectedObject->Distance = 1.9079905786448228;
		$expectedArray[] = $expectedObject;

		$this->assertEquals($expectedArray, $actualArray);
	}

	/**
	 * Test getSearchEvents function for past events.
	 */
	public function testGetSearchEventsPast() {
		$actualArray = static::$eventModel->getSearchEvents(1, 5, 40.1, -88.2, '', true);

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
		$expectedObject->FormattedDateTime = '12/31/2015 05:00 PM';
		$expectedObject->TagName = 'Running';
		$expectedObject->Distance = 1.5828691942519846;
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
		$expectedObject->FormattedDateTime = date('m/d/Y', strtotime('tomorrow')) . ' 05:00 PM';
		$expectedObject->TagName = 'Badminton';
		$expectedObject->Distance = 1.9079905786448228;
		$expectedArray[] = $expectedObject;

		$this->assertEquals($expectedArray, $actualArray);
	}

	/**
	 * Test the normal usage for getFeed function.
	 */
	public function testGetFeed() {
		$actualArray = static::$eventModel->getFeed(3);

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

}