<?php

include_once(__DIR__ . '/../../utils/dbTestCase.php');

abstract class ViewTestCase extends PHPUnit_Extensions_Selenium2TestCase {

	protected static $dbTestCaseWrapper;
	protected $applicationURL = 'http://pnp-test.local/';

	public static function setUpBeforeClass() {
		static::$dbTestCaseWrapper = new DBTestCaseWrapper();
	}

	public function setUp() {
		$this->setBrowser('firefox');
		$this->setBrowserUrl($this->applicationURL);

		static::$dbTestCaseWrapper->resetDatabase();
	}

	public function setUpPage() {
		$this->currentWindow()->maximize();
	}

	public function loginToSite($email, $password) {
		$this->url($this->applicationURL);

		$signInLink = $this->byId('signInLink');
		$signInLink->click();
		usleep(500000);

		$emailField = $this->byCssSelector('#signinForm #email');
		$emailField->clear();
		$this->keys($email);

		$passwordField = $this->byCssSelector('#signinForm #password');
		$passwordField->clear();
		$this->keys($password);

		$form = $this->byId('signinForm');
		$form->submit();
		usleep(500000);
	}

	public function assertNotExists($elementID, $errorElementName) {
		try {
			$this->byId($elementID);
			$this->fail($errorElementName . ' exists.');
		}
		catch(PHPUnit_Extensions_Selenium2TestCase_WebDriverException $exception) {
			$this->assertEquals(PHPUnit_Extensions_Selenium2TestCase_WebDriverException::NoSuchElement, $exception ->getCode());
		}
	}

}

class DBTestCaseWrapper extends DBTestCase {

	public function resetDatabase() {
		parent::setUp();
	}

	// PHPUnit class exclusion is not working, so use dummy test to avoid "no tests" error
	public function testDummy() {
		$this->assertTrue(true);
	}

}