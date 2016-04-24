<?php

include_once(__DIR__ . "/../../../src/application/helpers/siteHelper.php");

class SiteHelperTest extends PHPUnit_Framework_TestCase {

	private static $siteHelper;

	public static function setUpBeforeClass() {
		static::$siteHelper = new SiteHelper();
	}

	public function testGetSession() {
		$_SESSION["application"] = "PLAN & PLAY";
		$this->assertEquals("PLAN & PLAY", static::$siteHelper->getSession("application"));
	}

	public function testGetSessionNotExists() {
		$this->assertEquals("", static::$siteHelper->getSession("application"));
	}

	public function testAddAlertSingle() {
		$_SESSION["alerts"] = "";
		static::$siteHelper->addAlert("info", "Hello World");

		$alerts = static::$siteHelper->getSession("alerts");

		$this->assertInternalType('array', $alerts);
		$this->assertCount(1, $alerts);

		$this->assertEquals("info", $alerts[0]->type);
		$this->assertEquals("Hello World", $alerts[0]->message);
	}

	public function testAddAlertMultiple() {
		$_SESSION["alerts"] = "";
		static::$siteHelper->addAlert("info", "Hello World");
		static::$siteHelper->addAlert("danger", "Error Message");

		$alerts = static::$siteHelper->getSession("alerts");

		$this->assertInternalType('array', $alerts);
		$this->assertCount(2, $alerts);

		$this->assertEquals("info", $alerts[0]->type);
		$this->assertEquals("Hello World", $alerts[0]->message);

		$this->assertEquals("danger", $alerts[1]->type);
		$this->assertEquals("Error Message", $alerts[1]->message);
	}

	private function addDummyAlert() {
		$_SESSION["alerts"] = "";
		static::$siteHelper->addAlert("info", "Hello World");
	}

	private function checkAlert($html) {
		$this->assertEquals($html, static::$siteHelper->getAlertsHTML());
		$this->assertEquals("", static::$siteHelper->getSession("alerts"));
	}

	public function testGetAlertsHTMLSingle() {
		$this->addDummyAlert();

		$html = "<div class='alert alert-info' role='alert'><span>Hello World</span><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";

		$this->checkAlert($html);
	}

	public function testGetAlertsHTMLMultiple() {
		$this->addDummyAlert();
		static::$siteHelper->addAlert('danger', 'Error Message');

		$html = "<div class='alert alert-info' role='alert'><span>Hello World</span><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div><div class='alert alert-danger' role='alert'><span>Error Message</span><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";

		$this->checkAlert($html);
	}

	public function testSetPopUp() {
		static::$siteHelper->setPopUp('abc');

		$this->assertTrue(array_key_exists($_SESSION, "popup"));
		$this->assertEquals('abc', $_SESSION["popup"]->modalID);
	}

}
