<?php

include_once(__DIR__ . "/../../../src/application/helpers/siteHelper.php");

class SiteHelperTest extends PHPUnit_Framework_TestCase
{

	private static $siteHelper;

	public static function setUpBeforeClass() {
		static::$siteHelper = new SiteHelper();
	}

	public function testGetSession()
	{
		$_SESSION["application"] = "PLAN n PLAY";
		$this->assertEquals("PLAN n PLAY", static::$siteHelper->getSession("application"));
	}

	public function testAddAlertSingle()
	{
		$_SESSION["alerts"] = "";
		static::$siteHelper->addAlert("info", "Hello World");

		$alerts = static::$siteHelper->getSession("alerts");

		$this->assertInternalType('array', $alerts);
		$this->assertCount(1, $alerts);

		$this->assertEquals("info", $alerts[0]->type);
		$this->assertEquals("Hello World", $alerts[0]->message);
	}

	public function testAddAlertMultiple()
	{
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
	
	private function addDummyAlert()
	{
		$_SESSION["alerts"] = "";
		static::$siteHelper->addAlert("info", "Hello World");
	}
	
	private function checkAlert()
	{
		$this->assertEquals($html, static::$siteHelper->getAlertsHTML());
		$this->assertEquals("", static::$siteHelper->getSession("alerts"));
	}

	public function testGetAlertsHTMLSingle()
	{
		//$_SESSION["alerts"] = "";
		//static::$siteHelper->addAlert("info", "Hello World");
		addDummyAlert();
		
		$html = "<div class='alert alert-info' role='alert'>Hello World</div>";
		
		checkAlert();
		//$this->assertEquals($html, static::$siteHelper->getAlertsHTML());
		//$this->assertEquals("", static::$siteHelper->getSession("alerts"));
	}

	public function testGetAlertsHTMLMultiple()
	{
		//$_SESSION["alerts"] = "";
		//static::$siteHelper->addAlert("info", "Hello World");
		addDummyAlert();
		static::$siteHelper->addAlert("danger", "Error Message");

		$html = "<div class='alert alert-info' role='alert'>Hello World</div><div class='alert alert-danger' role='alert'>Error Message</div>";
		
		checkAlert();
		//$this->assertEquals($html, static::$siteHelper->getAlertsHTML());
		//$this->assertEquals("", static::$siteHelper->getSession("alerts"));
	}

}
