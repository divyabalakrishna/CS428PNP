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

	public function testSetAlert()
	{
		static::$siteHelper->setAlert("info", "Hello World");
		$alert = static::$siteHelper->getSession("alert");
		$this->assertEquals("info", $alert->type);
		$this->assertEquals("Hello World", $alert->message);
	}

	public function testGetAlertHTML()
	{
		static::$siteHelper->setAlert("info", "Hello World");
		$html = "<div class='alert alert-info' role='alert'>Hello World</div>";
		$this->assertEquals($html, static::$siteHelper->getAlertHTML());
		$this->assertEquals("", static::$siteHelper->getSession("alert"));
	}

}