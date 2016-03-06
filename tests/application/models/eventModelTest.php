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

	public function testInsertEvent()
	{
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

		$this->assertEquals(3, $this->getConnection()->getRowCount('event'));
		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	public function testDeleteEvent1()
	{
		static::$eventModel->deleteParticipant(1);
		static::$eventModel->deleteEvent(1, 1);

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'Event' => array()
		)))->getTable('Event');

		$actualTable = $this->getConnection()->createQueryTable('Event', 'SELECT * FROM Event WHERE EventID = 1');

		$this->assertEquals(1, $this->getConnection()->getRowCount('event'));
		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	public function testDeleteEvent2()
	{
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

		$this->assertEquals(2, $this->getConnection()->getRowCount('event'));
		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	public function testUpdateEvent1()
	{
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

	public function testUpdateEvent2()
	{
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

	public function testGetEvent1()
	{
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

	public function testGetEvent2()
	{
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

	public function testGetEvent3()
	{
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

}