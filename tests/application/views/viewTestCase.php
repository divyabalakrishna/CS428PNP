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

}

class DBTestCaseWrapper extends DBTestCase {

	public function resetDatabase() {
		parent::setUp();
	}

}