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

		$expectedTable = (new PHPUnit_ArrayDataSet(array(
			'User' => array(
				array('UserID' => 4,
						'FirstName' => null,
						'LastName' => null,
						'Email' => 'email@email.com',
						'Phone' => null,
						'Picture' => null,
						'Radius' => 5,
						'Reminder' => null,
						'NickName' => null,
						'Gender' => null,
						'BirthDate' => null,
						'Active' => '987xyz'
				)
			)
		)))->getTable('User');

		$actualTable = $this->getConnection()->createQueryTable('User', 'SELECT * FROM User WHERE UserID = ' . $userID);
		$filteredTable = new PHPUnit_Extensions_Database_DataSet_TableFilter($actualTable, array('Password'));

		$actualPassword = $actualTable->getValue(0, 'Password');

		$this->assertEquals(4, $this->getConnection()->getRowCount('User'));
		$this->assertTablesEqual($expectedTable, $filteredTable);
		$this->assertTrue(password_verify('abc123', $actualPassword));
	}

}