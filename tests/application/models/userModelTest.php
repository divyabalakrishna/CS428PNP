<?php

include_once (__DIR__ . '/modelTestCase.php');
include_once (__DIR__ . '/../../../src/application/models/userModel.php');

class UserModelTest extends ModelTestCase {

	private static $userModel;

	public static function setUpBeforeClass() {
		static::$userModel = new UserModel(parent::getPDO());
		parent::setGlobalVariables();
	}

	public function testLoginInfo() {
		$actualObject = static::$userModel->getLoginInfo('jsmith@email.com');

		// Password requires a separate method to check so store the value and remove it from the object
		$actualPassword = $actualObject->Password;
		unset($actualObject->Password);

		$expectedObject = new stdClass();
		$expectedObject->UserID = 2;
		$expectedObject->Email = 'jsmith@email.com';
		$expectedObject->Active = 'Yes';

		$this->assertEquals($expectedObject, $actualObject);
		$this->assertTrue(password_verify('abcde', $actualPassword));
	}

	public function testGetProfile() {
		$actualObject = static::$userModel->getProfile(1);

		$expectedObject = new stdClass();
		$expectedObject->FirstName = 'Jane';
		$expectedObject->LastName = 'Doe';
		$expectedObject->Email = 'jdoe@email.com';
		$expectedObject->Phone = '123-456-7890';
		$expectedObject->Picture = '1.png';
		$expectedObject->Radius = 5;
		$expectedObject->Reminder = 120;
		$expectedObject->NickName = 'Jane';
		$expectedObject->FormattedDate = '01/01/1990';
		$expectedObject->Gender = 'F';
		$expectedObject->Active = 'Yes';

		$this->assertEquals($expectedObject, $actualObject);
	}

	public function testInsertUser() {
		$userID = static::$userModel->insertUser('email@email.com', 'abc123', '987xyz');

		$expectedUser = parent::createUserObject(5, null, null, 'email@email.com', null, null, null, 5, null, null, null, null, '987xyz');
		unset($expectedUser['Password']);

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'User' => array($expectedUser)
		)))->getTable('User');

		$actualTable = $this->getConnection()->createQueryTable('User', 'SELECT * FROM User WHERE UserID = ' . $userID);
		$filteredTable = new PHPUnit_Extensions_Database_DataSet_TableFilter($actualTable, array('Password'));

		$actualPassword = $actualTable->getValue(0, 'Password');

		$this->assertEquals(5, $this->getConnection()->getRowCount('User'));
		$this->assertTablesEqual($expectedTable, $filteredTable);
		$this->assertTrue(password_verify('abc123', $actualPassword));
	}

	public function testIsActive() {
		$actualObject = static::$userModel->isActive(1);

		$expectedObject = new stdClass();
		$expectedObject->Active = 'Yes';

		$this->assertEquals($expectedObject, $actualObject);
	}

	public function testSetActive() {
		static::$userModel->setActive(4, 'Yes');

		$expectedUser = parent::createUserObject(4, 'First', 'Last', 'firstlast@email.com', '98765', '678-901-2345', '4.jpg', 5, 60, null, 'F', '1987-04-01', 'Yes');
		unset($expectedUser['Password']);

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'User' => array($expectedUser)
		)))->getTable('User');

		$actualTable = $this->getConnection()->createQueryTable('User', 'SELECT * FROM User WHERE UserID = 4');
		$filteredTable = new PHPUnit_Extensions_Database_DataSet_TableFilter($actualTable, array('Password'));

		$this->assertTablesEqual($expectedTable, $filteredTable);
	}
}