<?php

include_once (__DIR__ . '/modelTestCase.php');
include_once (__DIR__ . '/../../../src/application/models/userModel.php');

class UserModelTest extends ModelTestCase {

	private static $userModel;

	public static function setUpBeforeClass() {
		static::$userModel = new UserModel(parent::getPDO());
		parent::setGlobalVariables();
	}

	public function testGetLoginInfo() {
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

	public function testUpdateUser() {
		$userID = static::$userModel->updateUser(2, 'John', 'Smith', 'jsmith@email.com', 'a1b2c3d4e5', '789-012-3456', 'JS', 'M', '12/31/1985');

		$expectedUser = parent::createUserObject(2, 'John', 'Smith', 'jsmith@email.com', 'a1b2c3d4e5', '789-012-3456', '2.png', 5, 60, 'JS', 'M', '1985-12-31','Yes');
		unset($expectedUser['Password']);

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
				'User' => array($expectedUser)
		)))->getTable('User');

		$actualTable = $this->getConnection()->createQueryTable('User', 'SELECT * FROM User WHERE UserID = 2');
		$filteredTable = new PHPUnit_Extensions_Database_DataSet_TableFilter($actualTable, array('Password'));

		$actualPassword = $actualTable->getValue(0, 'Password');

		$this->assertTablesEqual($expectedTable, $filteredTable);
		$this->assertTrue(password_verify('a1b2c3d4e5', $actualPassword));
	}

	public function testSetPasswordEncrypted() {
		static::$userModel->setPassword(2, 'a1b2c3d4e5', 'yes');

		$actualTable = $this->getConnection()->createQueryTable('User', 'SELECT Password FROM User WHERE UserID = 2');
		$actualPassword = $actualTable->getValue(0, 'Password');

		$this->assertTrue(password_verify('a1b2c3d4e5', $actualPassword));
	}

	public function testSetPasswordNotEncrypted() {
		static::$userModel->setPassword(2, 'a1b2c3d4e5', 'no');

		$actualTable = $this->getConnection()->createQueryTable('User', 'SELECT Password FROM User WHERE UserID = 2');
		$actualPassword = $actualTable->getValue(0, 'Password');

		$this->assertEquals('a1b2c3d4e5', $actualPassword);
	}

	public function testDeleteUserTags() {
		static::$userModel->deleteUserTags(1);

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
				'UserTag' => array()
		)))->getTable('UserTag');

		$actualTable = $this->getConnection()->createQueryTable('UserTag', 'SELECT * FROM UserTag WHERE UserID = 1');

		$this->assertEquals(1, $this->getConnection()->getRowCount('UserTag'));
		$this->assertTablesEqual($expectedTable, $actualTable);
	}

	public function testGetAllUserIDs() {
		$actualArray = static::$userModel->getAllUserIDs();
	
		$expectedArray = array();
	
		$expectedObject = new stdClass();
		$expectedObject->UserID = 1;
		$expectedArray[] = $expectedObject;
	
		$expectedObject = new stdClass();
		$expectedObject->UserID = 2;
		$expectedArray[] = $expectedObject;

		$expectedObject = new stdClass();
		$expectedObject->UserID = 3;
		$expectedArray[] = $expectedObject;

		$expectedObject = new stdClass();
		$expectedObject->UserID = 4;
		$expectedArray[] = $expectedObject;

		$this->assertEquals($expectedArray, $actualArray);
	}

	public function testGetUserTags() {
		$actualArray = static::$userModel->getUserTags(1);

		$expectedArray = array();

		$expectedObject = (object)$this->createUserTagObject(1, 1);
		$expectedObject->TagName = 'American Football';
		$expectedArray[] = $expectedObject;

		$expectedObject = (object)$this->createUserTagObject(1, 5);
		$expectedObject->TagName = 'Basketball';
		$expectedArray[] = $expectedObject;
	
		$this->assertEquals($expectedArray, $actualArray);
	}

	public function testUpdatePicture() {
		static::$userModel->updatePicture(1, 'abc.jpg');

		$expectedUser = parent::createUserObject(1, 'Jane', 'Doe', 'jdoe@email.com', '12345', '123-456-7890', 'abc.jpg', 5, 120, 'Jane', 'F', '1990-01-01', 'Yes');
		unset($expectedUser['Password']);

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'User' => array(
				$expectedUser
			)
		)))->getTable('User');

		$actualTable = $this->getConnection()->createQueryTable('User', 'SELECT * FROM User WHERE UserID = 1');
		$filteredTable = new PHPUnit_Extensions_Database_DataSet_TableFilter($actualTable, array('Password'));

		$this->assertTablesEqual($expectedTable, $filteredTable);
	}

	public function testInsertUserTag() {
		static::$userModel->insertUserTag(1, 6);

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'UserTag' => array(
				parent::createUserTagObject(1, 1),
				parent::createUserTagObject(1, 5),
				parent::createUserTagObject(1, 6)
			)
		)))->getTable('UserTag');

		$actualTable = $this->getConnection()->createQueryTable('UserTag', 'SELECT * FROM UserTag WHERE UserID = 1');
	
		$this->assertEquals(4, $this->getConnection()->getRowCount('UserTag'));
		$this->assertTablesEqual($expectedTable, $actualTable);
	}

}