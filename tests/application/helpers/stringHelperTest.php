<?php

include_once(__DIR__ . "/../../../src/application/helpers/stringHelper.php");

class StringHelperTest extends PHPUnit_Framework_TestCase
{

	private static $string;
	private static $stringHelper;

	public static function setUpBeforeClass() {
		static::$string = "PLAN & PLAY";
		static::$stringHelper = new StringHelper();
	}

	public function testLeft()
	{
		$this->assertEquals("PLAN", static::$stringHelper->left(static::$string, 4));
	}

	public function testRight()
	{
		$this->assertEquals("PLAY", static::$stringHelper->right(static::$string, 4));
	}

}