<?php

include_once(__DIR__ . '/../../utils/dbTestCase.php');

/**
 * This is a base class for all view test classes.
 */
abstract class ViewTestCase extends PHPUnit_Extensions_Selenium2TestCase {

	protected static $dbTestCaseWrapper;
	protected $applicationURL = 'http://pnp-test.local/';

	/**
	 * Initialize required variables once for all tests.
	 */
	public static function setUpBeforeClass() {
		static::$dbTestCaseWrapper = new DBTestCaseWrapper();
	}

	/**
	 * Initialize required settings before each test.
	 */
	public function setUp() {
		$this->setBrowser('firefox');
		$this->setBrowserUrl($this->applicationURL);

		static::$dbTestCaseWrapper->resetDatabase();
	}

	/**
	 * Initialize Selenium page.
	 */
	public function setUpPage() {
		$this->currentWindow()->maximize();
	}

	/**
	 * Instruct Selenium to login to the application.
	 * @param string $email Email address.
	 * @param string $password Password.
	 */
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

	/**
	 * Assert that the element does not exist on the current page.
	 * @param unknown $elementID Element ID.
	 * @param unknown $errorElementName Words to be appended to the error message '<...> exists.'
	 */
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

/**
 * This is a wrapper class for DBTestCase.
 * This class is required because of the following reasons:
 * 1. Multiple class extensions are not allowed, but ViewTestCase needs to extend PHPUnit_Extensions_Selenium2TestCase in order for the Selenium tests to work.
 * 2. DBTestCase is an abstract class, thus it does not allow an instance to be created.
 */
class DBTestCaseWrapper extends DBTestCase {

	/**
	 * Reset test database with a fresh test dataset.
	 */
	public function resetDatabase() {
		parent::setUp();
	}

	/**
	 * Dummy test to avoid "no tests" error since PHPUnit class exclusion is not working.
	 */
	public function testDummy() {
		$this->assertTrue(true);
	}

}