<?php
include_once (__DIR__ . '/modelTestCase.php');
include_once (__DIR__ . '/../../../src/application/models/userModel.php');

class UserModelTest extends ModelTestCase {
	private static $userModel;
	public static function setUpBeforeClass() {
		static::$userModel = new UserModel ( parent::getPDO () );
		parent::setGlobalVariables ();
	}
	public function testGetProfile() {
		$actualObject = static::$userModel->getProfile (1);
		
		$expectedObject = new stdClass ();
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
		
		
		$this->assertEquals ( $expectedObject, $actualObject );
	}
// 	public function testGetLoginInfo(){
// 		$actualObject = static::$userModel->getLoginInfo ('jdoe@email.com');
		
// 		$expectedObject = new stdClass ();
// 		$expectedObject->UserID = '1';
// 		$expectedObject->Password = password_hash('12345', PASSWORD_DEFAULT);
// 		$expectedObject->Email = 'jdoe@email.com';
		
		
// 		$this->assertEquals ( $expectedObject, $actualObject );
// 	}
}